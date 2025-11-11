<?php

namespace App\Http\Controllers;

use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use App\Models\BreakLog;
use App\Models\PayrollReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        // Get recent payroll reports
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
            ->with('shift')
            ->orderBy('shift_date', 'desc')
            ->paginate(15);
        return view('employee.shifts.index', compact('shifts'));
    }

    public function attendance()
    {
        $user = Auth::user();

        // Get accepted shifts with their attendance records
        $acceptedShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('status', 'accepted')
            ->with(['shift'])
            ->latest('shift_date')
            ->paginate(15);

        // Load attendance logs separately to avoid the relationship issue
        foreach ($acceptedShifts as $shift) {
            $shift->attendanceLog = AttendanceLog::where('employee_id', $shift->employee_id)
                ->where('attendance_date', $shift->shift_date)
                ->first();
        }

        $thisMonthHours = AttendanceLog::where('employee_id', $user->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->sum('total_hours');

        return view('employee.attendance.index', compact('acceptedShifts', 'thisMonthHours'));
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

        // Use database transaction to ensure atomicity
        try {
            return DB::transaction(function () use ($employeeShift) {
                // Lock the record for update to prevent race conditions
                $employeeShift = EmployeeShift::lockForUpdate()->find($employeeShift->id);

                if (!$employeeShift) {
                    return response()->json(['error' => 'Shift assignment not found'], 404);
                }

                if (!in_array($employeeShift->status, ['pending', 'assigned'])) {
                    return response()->json(['error' => 'Shift is not available for acceptance. Current status: ' . $employeeShift->status], 400);
                }

                // Check if shift is at full capacity
                $capacityQuery = EmployeeShift::where('shift_id', $employeeShift->shift_id)
                    ->where('status', 'accepted');

                if ($employeeShift->shift_date) {
                    $capacityQuery->where('shift_date', $employeeShift->shift_date);
                }

                $currentAssignments = $capacityQuery->count();

                if ($currentAssignments >= $employeeShift->shift->max_capacity) {
                    return response()->json(['error' => 'Shift is at full capacity (' . $employeeShift->shift->max_capacity . ' employees max). Cannot accept this shift.'], 400);
                }

                // Update shift status
                $employeeShift->update([
                    'status' => 'accepted',
                    'responded_at' => now(),
                ]);

                // Auto-add hours to attendance when shift is accepted
                try {
                    $this->addShiftHoursToAttendance($employeeShift);
                } catch (\Exception $e) {
                    // Log the attendance creation error
                    Log::error('Failed to create attendance record for shift acceptance', [
                        'employee_shift_id' => $employeeShift->id,
                        'employee_id' => $employeeShift->employee_id,
                        'shift_id' => $employeeShift->shift_id,
                        'error' => $e->getMessage()
                    ]);

                    // If attendance creation fails, rollback the transaction
                    throw new \Exception('Failed to create attendance record: ' . $e->getMessage());
                }

                return response()->json(['success' => 'Shift accepted successfully']);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific errors
            Log::error('Database error during shift acceptance', [
                'employee_shift_id' => $employeeShift->id,
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
                'employee_shift_id' => $employeeShift->id,
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

    public function payroll()
    {
        $user = Auth::user();

        // Get monthly pay based on attendance and projections
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        // Calculate previous month hours and pay from actual attendance
        $previousMonthAttendance = AttendanceLog::where('employee_id', $user->id)
            ->where('status', 'present')
            ->whereBetween('attendance_date', [$previousMonth, $previousMonth->copy()->endOfMonth()])
            ->get();

        $previousMonthHours = $previousMonthAttendance->sum('total_hours');
        $previousMonthPay = $previousMonthHours * $user->hourly_rate;

        // Calculate current month projection from accepted shifts
        $currentMonthShifts = EmployeeShift::where('employee_id', $user->id)
            ->where('status', 'accepted')
            ->whereBetween('shift_date', [$currentMonth, $currentMonth->copy()->endOfMonth()])
            ->with('shift')
            ->get();

        // Calculate current month hours and pay
        $currentMonthHours = 0;
        $currentMonthPay = 0;

        foreach ($currentMonthShifts as $shiftAssignment) {
            if ($shiftAssignment->shift) {
                $startTime = $shiftAssignment->shift->start_time;
                $endTime = $shiftAssignment->shift->end_time;

                if ($endTime < $startTime) {
                    $endTime = $endTime->addDay();
                }

                $hours = $endTime->diffInHours($startTime);
                $currentMonthHours += $hours;
                $currentMonthPay += $hours * $user->hourly_rate;
            }
        }

        // Get all attendance records with pagination
        $attendanceRecords = AttendanceLog::where('employee_id', $user->id)
            ->where('status', 'present')
            ->orderBy('attendance_date', 'desc')
            ->paginate(12); // 12 months per page

        return view('employee.payroll.index', compact(
            'previousMonthHours',
            'previousMonthPay',
            'currentMonthHours',
            'currentMonthPay',
            'attendanceRecords',
            'user'
        ));
    }

    private function addShiftHoursToAttendance(EmployeeShift $employeeShift)
    {
        $shift = $employeeShift->shift;

        if (!$shift) {
            return;
        }

        // Skip attendance creation for recurring shifts without specific dates
        if (!$employeeShift->shift_date) {
            Log::info('Skipping attendance creation for recurring shift without date', [
                'employee_shift_id' => $employeeShift->id,
                'shift_id' => $shift->id,
            ]);
            return;
        }

        // Calculate shift duration in hours
        $startTime = Carbon::createFromTimeString($shift->start_time->format('H:i:s'));
        $endTime = Carbon::createFromTimeString($shift->end_time->format('H:i:s'));

        // Handle shifts that cross midnight
        if ($endTime->lt($startTime)) {
            $endTime->addDay();
        }

        $totalMinutes = $endTime->diffInMinutes($startTime);
        $totalHours = round($totalMinutes / 60, 2);

        // Check if attendance already exists for this date
        $existingAttendance = AttendanceLog::where('employee_id', $employeeShift->employee_id)
            ->where('attendance_date', $employeeShift->shift_date)
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
                'employee_id' => $employeeShift->employee_id,
                'shift_id' => $shift->id,
                'attendance_date' => $employeeShift->shift_date,
                'total_hours' => $totalHours,
                'total_hours_minutes' => $totalMinutes,
                'status' => 'present',
                'is_manual_entry' => false,
            ]);
        }
    }
}
