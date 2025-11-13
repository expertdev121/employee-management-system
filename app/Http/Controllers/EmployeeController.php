<?php

namespace App\Http\Controllers;

use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use App\Models\BreakLog;
use App\Models\PayrollReport;
use App\Models\EmployeePayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request as FacadesRequest;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $todayShifts = EmployeeShift::where('employee_id', $user->id)
            ->where(function ($query) {
                $query->where('shift_date', today())
                    ->orWhereNull('shift_date');
            })
            ->whereIn('status', ['accepted', 'assigned'])
            ->with('shift')
            ->get();

        $upcomingShifts = EmployeeShift::where('employee_id', $user->id)
            ->where(function ($query) {
                $query->where('shift_date', '>', today())
                    ->orWhereNull('shift_date');
            })
            ->whereIn('status', ['accepted', 'assigned'])
            ->with('shift')
            ->orderBy('shift_date')
            ->take(5)
            ->get();

        $pendingRequests = EmployeeShift::where('employee_id', $user->id)
            ->where('status', 'pending')
            ->with('shift')
            ->get();

        $recentAttendance = AttendanceLog::where('employee_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $thisMonthHours = AttendanceLog::where('employee_id', $user->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->sum('total_hours');

        $recentPayrolls = PayrollReport::where('employee_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('employee.dashboard', compact(
            'todayShifts',
            'upcomingShifts',
            'pendingRequests',
            'recentAttendance',
            'thisMonthHours',
            'recentPayrolls'
        ));
    }

    public function shifts()
    {
        $user = Auth::user();
        $shifts = EmployeeShift::where('employee_id', $user->id)
            ->where('status', '!=', 'unassigned')
            ->with('shift')
            ->orderBy('shift_date', 'desc')
            ->paginate(15);
        return view('employee.shifts.index', compact('shifts'));
    }

    public function attendance()
    {
        $user = Auth::user();

        // Get all attendance logs for the employee, including those from unassigned shifts
        $attendanceLogs = AttendanceLog::where('employee_id', $user->id)
            ->with(['shift'])
            ->latest('attendance_date')
            ->paginate(15);

        // Load the corresponding employee shift if it exists (even if unassigned)
        foreach ($attendanceLogs as $attendance) {
            $attendance->employeeShift = EmployeeShift::where('employee_id', $user->id)
                ->where('shift_id', $attendance->shift_id)
                ->when($attendance->shift_date, function ($query) use ($attendance) {
                    return $query->where('shift_date', $attendance->attendance_date);
                })
                ->first();
        }

        $thisMonthHours = AttendanceLog::where('employee_id', $user->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->sum('total_hours');

        return view('employee.attendance.index', compact('attendanceLogs', 'thisMonthHours'));
    }

    public function requests(Request $request)
    {
        $user = Auth::user();
        $query = EmployeeShift::where('employee_id', $user->id)
            ->with('shift')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15)->appends($request->query());
        $currentStatus = $request->get('status', 'all');

        return view('employee.requests.index', compact('requests', 'currentStatus'));
    }

    public function profile()
    {
        $user = Auth::user();

        $totalShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        $totalHours = AttendanceLog::where('employee_id', $user->id)
            ->sum('total_hours');

        return view('employee.profile.index', compact('user', 'totalShifts', 'totalHours'));
    }

    public function acceptShift(Request $request, EmployeeShift $employeeShift)
    {
        if ($employeeShift->employee_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // capture IDs so they're always available in catch blocks
        $employeeShiftId = $employeeShift->id;
        $employeeId = $employeeShift->employee_id;

        try {
            return DB::transaction(function () use ($employeeShiftId, $employeeId) {
                // Lock the record for update to prevent race conditions
                $lockedShift = EmployeeShift::lockForUpdate()->find($employeeShiftId);

                if (!$lockedShift) {
                    return response()->json(['error' => 'Shift assignment not found'], 404);
                }

                if (!in_array($lockedShift->status, ['pending', 'assigned'])) {
                    return response()->json(['error' => 'Shift is not available for acceptance. Current status: ' . $lockedShift->status], 400);
                }

                // Check if shift is at full capacity
                $capacityQuery = EmployeeShift::where('shift_id', $lockedShift->shift_id)
                    ->where('status', 'accepted');

                // we intentionally do not depend on shift_date for capacity checks as requested
                $currentAssignments = $capacityQuery->count();

                if ($currentAssignments >= $lockedShift->shift->max_capacity) {
                    return response()->json(['error' => 'Shift is at full capacity (' . $lockedShift->shift->max_capacity . ' employees max). Cannot accept this shift.'], 400);
                }

                // Update shift status
                $lockedShift->update([
                    'status' => 'accepted',
                    'responded_at' => now(),
                ]);

                // Auto-add hours to attendance when shift is accepted
                try {
                    $this->addShiftHoursToAttendance($lockedShift);
                } catch (\Exception $e) {
                    // Log the attendance creation error
                    Log::error('Failed to create attendance record for shift acceptance', [
                        'employee_shift_id' => $employeeShiftId,
                        'employee_id' => $employeeId,
                        'shift_id' => $lockedShift->shift_id,
                        'error' => $e->getMessage()
                    ]);

                    // If attendance creation fails, rollback the transaction
                    throw new \Exception('Failed to create attendance record: ' . $e->getMessage());
                }

                // Load relationships needed for payroll creation
                $lockedShift->load(['employee', 'shift']);

                // Create employee payroll record
                try {
                    $this->createEmployeePayrollRecord($lockedShift);
                } catch (\Exception $e) {
                    // Log the payroll creation error
                    Log::error('Failed to create payroll record for shift acceptance', [
                        'employee_shift_id' => $employeeShiftId,
                        'employee_id' => $employeeId,
                        'shift_id' => $lockedShift->shift_id,
                        'error' => $e->getMessage()
                    ]);

                    // If payroll creation fails, rollback the transaction
                    throw new \Exception('Failed to create payroll record: ' . $e->getMessage());
                }

                return response()->json(['success' => 'Shift accepted successfully']);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific errors
            Log::error('Database error during shift acceptance', [
                'employee_shift_id' => $employeeShiftId ?? null,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

            if ($e->getCode() == 23000) { // Integrity constraint violation
                return response()->json(['error' => 'Database constraint violation. This shift may already be accepted or there\'s a data conflict.'], 400);
            }

            return response()->json(['error' => 'Database error occurred. Please try again.'], 500);
        } catch (\Exception $e) {
            // Handle any other exceptions
            Log::error('Unexpected error during shift acceptance', [
                'employee_shift_id' => $employeeShiftId ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function rejectShift(Request $request, EmployeeShift $employeeShift)
    {
        if ($employeeShift->employee_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!in_array($employeeShift->status, ['pending', 'assigned'])) {
            return response()->json(['error' => 'Shift is not available for rejection'], 400);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $employeeShift->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'responded_at' => now(),
        ]);

        return response()->json(['success' => 'Shift rejected successfully']);
    }



    public function showShift(EmployeeShift $employeeShift)
    {
        if ($employeeShift->employee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $employeeShift->load(['shift', 'employee']);

        return view('employee.shifts.show', compact('employeeShift'));
    }

    public function payroll(Request $request)
    {
        $user = Auth::user();

        $query = EmployeePayroll::where('employee_id', $user->id)
            ->with(['employeeShift.shift'])
            ->orderBy('shift_date', 'desc');

        // Date range filter: start_date and end_date (YYYY-MM-DD)
        $start = $request->filled('start_date') ? $request->start_date : null;
        $end = $request->filled('end_date') ? $request->end_date : null;

        try {
            if ($start && $end) {
                $startDate = Carbon::parse($start)->startOfDay()->toDateString();
                $endDate = Carbon::parse($end)->endOfDay()->toDateString();
                // whereDate between start and end (inclusive)
                $query->whereBetween('shift_date', [$startDate, $endDate]);
            } elseif ($start) {
                $startDate = Carbon::parse($start)->toDateString();
                $query->whereDate('shift_date', '>=', $startDate);
            } elseif ($end) {
                $endDate = Carbon::parse($end)->toDateString();
                $query->whereDate('shift_date', '<=', $endDate);
            }
        } catch (\Exception $e) {
            // invalid date formats are ignored (logged) — falls back to no range filter
            Log::warning('Invalid payroll date range filter: ' . $e->getMessage());
        }

        // Optional: status filter if you want (uncomment to use)
        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }

        $payrollRecords = $query->paginate(10)->appends($request->query());

        // Recompute the summary cards (unchanged behaviour)
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        $previousMonthPayroll = EmployeePayroll::where('employee_id', $user->id)
            ->whereBetween('shift_date', [$previousMonth, $previousMonth->copy()->endOfMonth()])
            ->get();

        $previousMonthHours = $previousMonthPayroll->sum('total_hours');
        $previousMonthPay = $previousMonthPayroll->sum('total_pay');

        $currentMonthPayroll = EmployeePayroll::where('employee_id', $user->id)
            ->whereBetween('shift_date', [$currentMonth, $currentMonth->copy()->endOfMonth()])
            ->get();

        $currentMonthHours = $currentMonthPayroll->sum('total_hours');
        $currentMonthPay = $currentMonthPayroll->sum('total_pay');

        return view('employee.payroll.index', compact(
            'previousMonthHours',
            'previousMonthPay',
            'currentMonthHours',
            'currentMonthPay',
            'payrollRecords',
            'user'
        ));
    }

    /**
     * Add calculated shift hours to attendance for the current date.
     *
     * @param \App\Models\EmployeeShift $employeeShift
     * @return void
     */
    private function addShiftHoursToAttendance(EmployeeShift $employeeShift)
    {
        /** @var \App\Models\EmployeeShift $employeeShift */
        $es = $employeeShift; // local alias to satisfy static analyzers

        $shift = $es->shift;
        if (!$shift) {
            return;
        }

        // Always use current date for attendance
        $attendanceDate = now()->toDateString();

        // Calculate shift duration in hours using Carbon
        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        // Handle shifts that cross midnight
        if ($endTime->lt($startTime)) {
            $endTime->addDay();
        }

        $totalMinutes = $endTime->diffInMinutes($startTime);
        $totalHours = round($totalMinutes / 60, 2);

        // Check if attendance already exists for this date
        $existingAttendance = AttendanceLog::where('employee_id', $es->employee_id)
            ->where('attendance_date', $attendanceDate)
            ->first();

        if ($existingAttendance) {
            // Update existing attendance
            $existingAttendance->update([
                'total_hours' => $totalHours,
                'total_hours_minutes' => $totalMinutes,
                'status' => 'present',
                'shift_id' => $shift->id,
            ]);
        } else {
            // Create new attendance record
            AttendanceLog::create([
                'employee_id' => $es->employee_id,
                'shift_id' => $shift->id,
                'attendance_date' => $attendanceDate,
                'total_hours' => $totalHours,
                'total_hours_minutes' => $totalMinutes,
                'status' => 'present',
                'is_manual_entry' => false,
            ]);
        }
    }

    /**
     * Create or update payroll record for an accepted shift.
     *
     * @param \App\Models\EmployeeShift $employeeShift
     * @return void
     */
    private function createEmployeePayrollRecord(EmployeeShift $employeeShift)
    {
        /** @var \App\Models\EmployeeShift $employeeShift */
        $es = $employeeShift; // Local alias for clarity

        $shift = $es->shift;
        $employee = $es->employee;

        if (!$shift || !$employee) {
            return;
        }

        // Always use current date for payroll
        $payrollDate = now()->toDateString();

        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        // Handle shifts that cross midnight
        if ($endTime->lt($startTime)) {
            $endTime->addDay();
        }

        $totalMinutes = $endTime->diffInMinutes($startTime);
        $totalHours = round($totalMinutes / 60, 2);
        $totalPay = $totalHours * $employee->hourly_rate;

        // Check if payroll record already exists for this shift (by shift id + payrollDate)
        $existingPayroll = EmployeePayroll::where('employee_id', $es->employee_id)
            ->where('employee_shift_id', $es->id)
            ->where('shift_date', $payrollDate)
            ->first();

        if ($existingPayroll) {
            $existingPayroll->update([
                'hourly_rate' => $employee->hourly_rate,
                'total_hours' => $totalHours,
                'total_pay' => $totalPay,
                'accepted_at' => $es->responded_at ?? now(),
            ]);
        } else {
            EmployeePayroll::create([
                'employee_id' => $es->employee_id,
                'employee_shift_id' => $es->id,
                'shift_date' => $payrollDate,
                'hourly_rate' => $employee->hourly_rate,
                'total_hours' => $totalHours,
                'total_pay' => $totalPay,
                'status' => 'active',
                'accepted_at' => $es->responded_at ?? now(),
            ]);
        }
    }
}
