<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use App\Models\PayrollReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request as FacadesRequest;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = User::employees()->active()->count();
        $totalShifts = Shift::active()->count();
        $pendingShiftRequests = EmployeeShift::whereIn('status', ['pending', 'assigned'])->count();
        $todayAttendance = AttendanceLog::where('attendance_date', today())->count();

        $recentAttendance = AttendanceLog::with(['employee', 'shift'])
            ->orderBy('attendance_date', 'desc')
            ->take(5)
            ->get();

        $assignedShifts = EmployeeShift::with(['employee', 'shift'])
            ->where('status', 'assigned')
            ->latest()
            ->take(5)
            ->get();

        $rejectedShifts = EmployeeShift::with(['employee', 'shift'])
            ->where('status', 'rejected')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalShifts',
            'pendingShiftRequests',
            'todayAttendance',
            'recentAttendance',
            'assignedShifts',
            'rejectedShifts'
        ));
    }

    public function exportDashboard()
    {
        $filename = 'dashboard_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($handle, ['Dashboard Export - ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, []); // Empty row for spacing

            // Statistics Section
            fputcsv($handle, ['STATISTICS']);
            fputcsv($handle, ['Metric', 'Value']);

            $totalEmployees = User::employees()->active()->count();
            $totalShifts = Shift::active()->count();
            $pendingShiftRequests = EmployeeShift::whereIn('status', ['pending', 'assigned'])->count();
            $todayAttendance = AttendanceLog::where('attendance_date', today())->count();

            fputcsv($handle, ['Total Employees', $totalEmployees]);
            fputcsv($handle, ['Active Shifts', $totalShifts]);
            fputcsv($handle, ['Pending Requests', $pendingShiftRequests]);
            fputcsv($handle, ['Today\'s Attendance', $todayAttendance]);

            fputcsv($handle, []); // Empty row

            // Recent Attendance Section
            fputcsv($handle, ['RECENT ATTENDANCE']);
            fputcsv($handle, ['Employee Name', 'Email', 'Attendance Status', 'Accepted Date', 'Login Time', 'Logout Time', 'Total Hours']);

            $recentAttendance = EmployeeShift::where('status', 'accepted')
                ->with(['employee', 'shift', 'attendanceLog'])
                ->orderBy('responded_at', 'desc')
                ->take(10)
                ->get();

            foreach ($recentAttendance as $shift) {
                $attendance = $shift->attendanceLog;
                fputcsv($handle, [
                    $shift->employee->name,
                    $shift->employee->email,
                    $attendance ? ucfirst($attendance->status) : 'Not Logged',
                    $shift->responded_at ? $shift->responded_at->format('Y-m-d') : 'N/A',
                    $attendance ? ($attendance->login_time ?? '') : '',
                    $attendance ? ($attendance->logout_time ?? '') : '',
                    $attendance ? ($attendance->total_hours ?? '') : ''
                ]);
            }

            fputcsv($handle, []); // Empty row

            // Assigned Shifts Section
            fputcsv($handle, ['ASSIGNED SHIFTS']);
            fputcsv($handle, ['Employee Name', 'Email', 'Shift Name', 'Shift Date', 'Status']);

            $assignedShifts = EmployeeShift::with(['employee', 'shift'])
                ->where('status', 'assigned')
                ->latest()
                ->take(5)
                ->get();

            foreach ($assignedShifts as $shift) {
                fputcsv($handle, [
                    $shift->employee->name,
                    $shift->employee->email,
                    $shift->shift->shift_name,
                    $shift->shift_date->format('Y-m-d'),
                    ucfirst($shift->status)
                ]);
            }

            fputcsv($handle, []); // Empty row

            // Rejected Shifts Section
            fputcsv($handle, ['REJECTED SHIFTS']);
            fputcsv($handle, ['Employee Name', 'Email', 'Shift Name', 'Shift Date', 'Status', 'Rejection Reason']);

            $rejectedShifts = EmployeeShift::with(['employee', 'shift'])
                ->where('status', 'rejected')
                ->latest()
                ->take(5)
                ->get();

            foreach ($rejectedShifts as $shift) {
                fputcsv($handle, [
                    $shift->employee->name,
                    $shift->employee->email,
                    $shift->shift->shift_name,
                    $shift->shift_date->format('Y-m-d'),
                    ucfirst($shift->status),
                    $shift->rejection_reason ?? ''
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Users CRUD (Employees and Clients)
    public function employees()
    {
        $employees = User::where('role', 'employee')->with('employeeShifts.shift')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.employees.form');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'social_id' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            // Removed role from validation because employees form no longer has role field
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department' => $request->department,
            'hourly_rate' => $request->hourly_rate,
            'social_id' => $request->social_id,
            'full_address' => $request->full_address,
            // Role fixed to 'employee' explicitly
            'role' => 'employee',
            'max_shifts_per_week' => null,
            'max_shifts_per_day' => 4,
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function showEmployee(User $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    public function editEmployee(User $employee)
    {
        return view('admin.employees.form', compact('employee'));
    }

    public function updateEmployee(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'social_id' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            // Role removed from required validation because employees form does not submit it
            'status' => 'required|in:active,inactive,blocked',
        ]);

        // Set role to employee explicitly to avoid missing role from form
        $data = $request->only(['name', 'email', 'phone', 'department', 'hourly_rate', 'social_id', 'full_address', 'status']);
        $data['role'] = 'employee';

        $employee->update($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroyEmployee(User $employee)
    {
        // Force delete to trigger cascade deletion of related records
        $employee->forceDelete();
        return redirect()->route('admin.employees.index')->with('success', 'User deleted successfully.');
    }

    // Clients CRUD
    public function clients()
    {
        $clients = User::clients()->with('employeeShifts.shift')->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }

    public function createClient()
    {
        return view('admin.clients.form');
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department' => $request->department,
            'hourly_rate' => $request->hourly_rate,
            'role' => 'client',
            'max_shifts_per_week' => 4,
        ]);

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully.');
    }

    public function showClient(User $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    public function editClient(User $client)
    {
        return view('admin.clients.form', compact('client'));
    }

    public function updateClient(Request $request, User $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'full_address' => 'nullable|string',
            'floor' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $client->update($request->only(['name', 'email', 'phone', 'department', 'hourly_rate', 'full_address', 'floor', 'business_name', 'status']));

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroyClient(User $client)
    {
        // Force delete to trigger cascade deletion of related records
        $client->forceDelete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    // Shifts CRUD
    public function shifts()
    {
        $shifts = Shift::with('employeeShifts.employee')->paginate(15);
        return view('admin.shifts.index', compact('shifts'));
    }

    public function createShift()
    {
        return view('admin.shifts.form');
    }

    public function storeShift(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_type' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created successfully.');
    }

    public function showShift(Shift $shift)
    {
        return view('admin.shifts.show', compact('shift'));
    }

    public function editShift(Shift $shift)
    {
        return view('admin.shifts.form', compact('shift'));
    }

    public function updateShift(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_type' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function destroyShift(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
    }

    public function assignEmployeeToShift(Request $request, Shift $shift)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Check if employee is already assigned to this shift on the same date (if date is provided)
        if ($request->shift_date) {
            $existingAssignment = EmployeeShift::where('employee_id', $request->employee_id)
                ->where('shift_id', $shift->id)
                ->where('shift_date', $request->shift_date)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()->with('error', 'Employee is already assigned to this shift on the selected date.');
            }

            // Check if shift is at full capacity for the specific date
            $currentAssignments = EmployeeShift::where('shift_id', $shift->id)
                ->where('shift_date', $request->shift_date)
                ->where('status', 'assigned')
                ->count();

            if ($currentAssignments >= $shift->max_capacity) {
                return redirect()->back()->with('error', 'Shift is at full capacity for the selected date. Cannot assign more employees.');
            }
        } else {
            // For recurring shifts without date, check if employee is already assigned to this shift
            $existingAssignment = EmployeeShift::where('employee_id', $request->employee_id)
                ->where('shift_id', $shift->id)
                ->whereNull('shift_date')
                ->first();

            if ($existingAssignment) {
                return redirect()->back()->with('error', 'Employee is already assigned to this recurring shift.');
            }
        }

        EmployeeShift::create([
            'employee_id' => $request->employee_id,
            'shift_id' => $shift->id,
            'shift_date' => $request->shift_date,
            'status' => 'assigned',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Employee assigned to shift successfully.');
    }

    public function unassignEmployeeFromShift(EmployeeShift $employeeShift)
    {
        if (!in_array($employeeShift->status, ['assigned', 'accepted'])) {
            return redirect()->back()->with('error', 'Only assigned or accepted shifts can be unassigned.');
        }

        $employeeShift->update([
            'status' => 'unassigned',
            'responded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Employee unassigned from shift successfully.');
    }

    // Attendance CRUD
    public function attendance(Request $request)
    {
        $query = AttendanceLog::with(['employee', 'shift']);

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->where('attendance_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('attendance_date', '<=', $request->end_date);
        }

        // Month filter
        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereYear('attendance_date', date('Y', strtotime($month)))
                  ->whereMonth('attendance_date', date('m', strtotime($month)));
        }

        $attendanceLogs = $query->orderBy('attendance_date', 'desc')->paginate(8);

        // Get employees for filter dropdown
        $employees = User::where('role', 'employee')->get();

        // Get filter values for form
        $employeeId = $request->employee_id;
        $month = $request->month;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return view('admin.attendance.index', compact('attendanceLogs', 'employees', 'employeeId', 'month', 'startDate', 'endDate'));
    }

    public function createAttendance()
    {
        $employees = User::employees()->active()->get();
        $shifts = Shift::active()->get();
        return view('admin.attendance.form', compact('employees', 'shifts'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'login_time' => 'nullable|date_format:H:i',
            'logout_time' => 'nullable|date_format:H:i|after:login_time',
            'status' => 'required|in:present,absent,late,early_leave,on_break',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Calculate total hours if both login and logout times are provided
        if ($request->login_time && $request->logout_time) {
            $loginTime = \Carbon\Carbon::createFromFormat('H:i', $request->login_time);
            $logoutTime = \Carbon\Carbon::createFromFormat('H:i', $request->logout_time);

            // Calculate total hours
            $totalHours = $logoutTime->diffInMinutes($loginTime) / 60;
            $data['total_hours'] = round($totalHours, 2);
        }

        AttendanceLog::create($data);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record created successfully.');
    }

    public function showAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->load(['employee', 'shift', 'approver']);
        return view('admin.attendance.show', compact('attendanceLog'));
    }

    public function editAttendance(AttendanceLog $attendanceLog)
    {
        $employees = User::employees()->active()->get();
        $shifts = Shift::active()->get();
        return view('admin.attendance.form', compact('attendanceLog', 'employees', 'shifts'));
    }

    public function updateAttendance(Request $request, AttendanceLog $attendanceLog)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'login_time' => 'nullable|date_format:H:i',
            'logout_time' => 'nullable|date_format:H:i|after:login_time',
            'status' => 'required|in:present,absent,late,early_leave,on_break',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Calculate total hours if both login and logout times are provided
        if ($request->login_time && $request->logout_time) {
            $loginTime = \Carbon\Carbon::createFromFormat('H:i', $request->login_time);
            $logoutTime = \Carbon\Carbon::createFromFormat('H:i', $request->logout_time);

            // Calculate total hours
            $totalHours = $logoutTime->diffInMinutes($loginTime) / 60;
            $data['total_hours'] = round($totalHours, 2);
        }

        $attendanceLog->update($data);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record updated successfully.');
    }

    public function destroyAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record deleted successfully.');
    }

    // Employee Attendance Methods
    public function employeeAttendance(Request $request)
    {
        $query = AttendanceLog::with(['employee', 'shift'])
            ->whereHas('employee', function ($q) {
                $q->where('role', 'employee');
            });

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Month filter
        if ($request->filled('month')) {
            $query->whereYear('attendance_date', date('Y', strtotime($request->month)))
                  ->whereMonth('attendance_date', date('m', strtotime($request->month)));
        }

        // Date range filters
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        $attendanceLogs = $query->orderBy('attendance_date', 'desc')->paginate(8);

        // Get employees for filter dropdown
        $employees = User::where('role', 'employee')->get();

        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $employeeId = $request->get('employee_id');

        return view('admin.employee-attendance.index', compact('attendanceLogs', 'employees', 'month', 'startDate', 'endDate', 'employeeId'));
    }

    public function createEmployeeAttendance()
    {
        $employees = User::where('role', 'employee')->get();
        $shifts = \App\Models\Shift::all();

        return view('admin.employee-attendance.create', compact('employees', 'shifts'));
    }

    public function storeEmployeeAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'total_hours' => 'required|numeric|min:0|max:24',
            'status' => 'required|in:present,absent,late',
            'is_manual_entry' => 'boolean',
        ]);

        AttendanceLog::create([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'attendance_date' => $request->attendance_date,
            'total_hours' => $request->total_hours,
            'total_hours_minutes' => $request->total_hours * 60,
            'status' => $request->status,
            'is_manual_entry' => $request->is_manual_entry ?? true,
        ]);

        return redirect()->route('admin.employee-attendance.index')->with('success', 'Employee attendance record created successfully.');
    }

    public function showEmployeeAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->load(['employee', 'shift']);

        return view('admin.employee-attendance.show', compact('attendanceLog'));
    }

    public function editEmployeeAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->load(['employee', 'shift']);
        $employees = User::where('role', 'employee')->get();
        $shifts = \App\Models\Shift::all();

        return view('admin.employee-attendance.edit', compact('attendanceLog', 'employees', 'shifts'));
    }

    public function updateEmployeeAttendance(Request $request, AttendanceLog $attendanceLog)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'total_hours' => 'required|numeric|min:0|max:24',
            'status' => 'required|in:present,absent,late',
            'is_manual_entry' => 'boolean',
        ]);

        $attendanceLog->update([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'attendance_date' => $request->attendance_date,
            'total_hours' => $request->total_hours,
            'total_hours_minutes' => $request->total_hours * 60,
            'status' => $request->status,
            'is_manual_entry' => $request->is_manual_entry ?? true,
        ]);

        return redirect()->route('admin.employee-attendance.index')->with('success', 'Employee attendance record updated successfully.');
    }

    public function destroyEmployeeAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();

        return redirect()->route('admin.employee-attendance.index')->with('success', 'Employee attendance record deleted successfully.');
    }

    // Client Attendance Methods
    public function clientAttendance(Request $request)
    {
        $query = AttendanceLog::with(['employee', 'shift'])
            ->whereHas('employee', function ($q) {
                $q->where('role', 'client');
            });

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Month filter
        if ($request->filled('month')) {
            $query->whereYear('attendance_date', date('Y', strtotime($request->month)))
                  ->whereMonth('attendance_date', date('m', strtotime($request->month)));
        }

        // Date range filters
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        $attendanceLogs = $query->orderBy('attendance_date', 'desc')->paginate(8);

        // Get clients for filter dropdown
        $employees = User::where('role', 'client')->get();

        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $employeeId = $request->get('employee_id');

        return view('admin.client-attendance.index', compact('attendanceLogs', 'employees', 'month', 'startDate', 'endDate', 'employeeId'));
    }

    public function createClientAttendance()
    {
        $employees = User::where('role', 'client')->get();
        $shifts = \App\Models\Shift::all();

        return view('admin.client-attendance.create', compact('employees', 'shifts'));
    }

    public function storeClientAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'total_hours' => 'required|numeric|min:0|max:24',
            'status' => 'required|in:present,absent,late',
            'is_manual_entry' => 'boolean',
        ]);

        AttendanceLog::create([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'attendance_date' => $request->attendance_date,
            'total_hours' => $request->total_hours,
            'total_hours_minutes' => $request->total_hours * 60,
            'status' => $request->status,
            'is_manual_entry' => $request->is_manual_entry ?? true,
        ]);

        return redirect()->route('admin.client-attendance.index')->with('success', 'Client attendance record created successfully.');
    }

    public function showClientAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->load(['employee', 'shift']);

        return view('admin.client-attendance.show', compact('attendanceLog'));
    }

    public function editClientAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->load(['employee', 'shift']);
        $employees = User::where('role', 'client')->get();
        $shifts = \App\Models\Shift::all();

        return view('admin.client-attendance.edit', compact('attendanceLog', 'employees', 'shifts'));
    }

    public function updateClientAttendance(Request $request, AttendanceLog $attendanceLog)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'attendance_date' => 'required|date',
            'total_hours' => 'required|numeric|min:0|max:24',
            'status' => 'required|in:present,absent,late',
            'is_manual_entry' => 'boolean',
        ]);

        $attendanceLog->update([
            'employee_id' => $request->employee_id,
            'shift_id' => $request->shift_id,
            'attendance_date' => $request->attendance_date,
            'total_hours' => $request->total_hours,
            'total_hours_minutes' => $request->total_hours * 60,
            'status' => $request->status,
            'is_manual_entry' => $request->is_manual_entry ?? true,
        ]);

        return redirect()->route('admin.client-attendance.index')->with('success', 'Client attendance record updated successfully.');
    }

    public function destroyClientAttendance(AttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();

        return redirect()->route('admin.client-attendance.index')->with('success', 'Client attendance record deleted successfully.');
    }

    // Payroll CRUD
    public function payroll(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $employeeName = $request->get('employee_name');
        $userType = $request->get('user_type');
        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Use EmployeePayroll model like EmployeeController for payroll data

        $query = \App\Models\EmployeePayroll::with(['employee'])
            ->when($employeeId, function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            });

        if ($month) {
            try {
                $parsedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month);
                $query->whereYear('shift_date', $parsedMonth->year)
                    ->whereMonth('shift_date', $parsedMonth->month);
            } catch (\Exception $e) {
                // ignore
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('shift_date', [$startDate, $endDate]);
        }

        $payrollData = $query->paginate(15)->appends($request->query());

        // Aggregation for summary cards
        $totalHours = $query->sum('total_hours');
        $totalPay = $query->sum('total_pay');

        // Instead of employeesWithPay and clientsWithPay from previous approach,
        // group EmployeePayroll by employee type and id and aggregate total hours and pay

$employeesWithPayQuery = \App\Models\User::employees()->active()
            ->when($employeeName, function ($q) use ($employeeName) {
                $q->where(function ($query) use ($employeeName) {
                    $query->where('name', 'like', '%' . $employeeName . '%')
                        ->orWhere('email', 'like', '%' . $employeeName . '%');
                });
            });

$employeesWithPay = $employeesWithPayQuery->paginate(15);
$employeesWithPay->getCollection()->transform(function ($employee) use ($startDate, $endDate, $month) {
    $payrollQuery = \App\Models\EmployeePayroll::where('employee_id', $employee->id);

    if ($month) {
        try {
            $parsedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month);
            $payrollQuery->whereYear('shift_date', $parsedMonth->year)
                ->whereMonth('shift_date', $parsedMonth->month);
        } catch (\Exception $e) {
            //
        }
    }

    if ($startDate && $endDate) {
        $payrollQuery->whereBetween('shift_date', [$startDate, $endDate]);
    }

    $totalHours = $payrollQuery->sum('total_hours');
    $totalPay = $payrollQuery->sum('total_pay');
    $shiftsCount = $payrollQuery->count();

    $employee->calculated_hours = $totalHours;
    $employee->calculated_pay = $totalPay;
    $employee->shifts_count = $shiftsCount;

    return $employee;
});

// Filter and re-paginate to maintain LengthAwarePaginator
$filteredEmployees = $employeesWithPay->getCollection()->filter(function ($emp) {
    return $emp->shifts_count > 0;
});
$employeesWithPay = new \Illuminate\Pagination\LengthAwarePaginator(
    $filteredEmployees->forPage($employeesWithPay->currentPage(), $employeesWithPay->perPage()),
    $filteredEmployees->count(),
    $employeesWithPay->perPage(),
    $employeesWithPay->currentPage(),
    ['path' => $employeesWithPay->path(), 'pageName' => $employeesWithPay->getPageName()]
);

$clientsWithPayQuery = \App\Models\User::clients()->active()
            ->when($employeeName, function ($q) use ($employeeName) {
                $q->where(function ($query) use ($employeeName) {
                    $query->where('name', 'like', '%' . $employeeName . '%')
                        ->orWhere('email', 'like', '%' . $employeeName . '%');
                });
            });

$clientsWithPay = $clientsWithPayQuery->paginate(15);
$clientsWithPay->getCollection()->transform(function ($client) use ($startDate, $endDate, $month) {
    $payrollQuery = \App\Models\EmployeePayroll::where('employee_id', $client->id);

    if ($month) {
        try {
            $parsedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month);
            $payrollQuery->whereYear('shift_date', $parsedMonth->year)
                ->whereMonth('shift_date', $parsedMonth->month);
        } catch (\Exception $e) {
            //
        }
    }

    if ($startDate && $endDate) {
        $payrollQuery->whereBetween('shift_date', [$startDate, $endDate]);
    }

    $totalHours = $payrollQuery->sum('total_hours');
    $totalPay = $payrollQuery->sum('total_pay');
    $shiftsCount = $payrollQuery->count();

    $client->calculated_hours = $totalHours;
    $client->calculated_pay = $totalPay;
    $client->shifts_count = $shiftsCount;

    return $client;
});

// Filter and re-paginate to maintain LengthAwarePaginator
$filteredClients = $clientsWithPay->getCollection()->filter(function ($client) {
    return $client->shifts_count > 0;
});
$clientsWithPay = new \Illuminate\Pagination\LengthAwarePaginator(
    $filteredClients->forPage($clientsWithPay->currentPage(), $clientsWithPay->perPage()),
    $filteredClients->count(),
    $clientsWithPay->perPage(),
    $clientsWithPay->currentPage(),
    ['path' => $clientsWithPay->path(), 'pageName' => $clientsWithPay->getPageName()]
);

        // For payroll reports, keep original pagination of PayrollReport model
        $payrollReportsQuery = \App\Models\PayrollReport::with(['employee', 'generator']);
        if ($employeeId) {
            $payrollReportsQuery->where('employee_id', $employeeId);
        }
        if ($month) {
            try {
                $parsedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month);
                $payrollReportsQuery->whereYear('period_start', $parsedMonth->year)
                    ->whereMonth('period_start', $parsedMonth->month);
            } catch (\Exception $e) {
                // ignore
            }
        }
        if ($startDate && $endDate) {
            $payrollReportsQuery->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('period_start', [$startDate, $endDate])
                    ->orWhereBetween('period_end', [$startDate, $endDate]);
            });
        }
        $payrollReports = $payrollReportsQuery->paginate(15)->appends($request->except('page'));

$employees = \App\Models\User::employees()->active()->get();
$clients = \App\Models\User::clients()->active()->get();

$totalProjectedPay = $employeesWithPay->sum('calculated_pay');
$totalClientProjectedPay = $clientsWithPay->sum('calculated_pay');

return view('admin.payroll.index', compact(
            'payrollReports',
            'totalProjectedPay',
            'totalClientProjectedPay',
            'employeesWithPay',
            'clientsWithPay',
            'employeeId',
            'employeeName',
            'userType',
            'month',
            'startDate',
            'endDate',
            'employees',
            'clients'
        ));
    }

    public function createPayroll()
    {
        $employees = User::employees()->active()->get();
        return view('admin.payroll.form', compact('employees'));
    }

    public function storePayroll(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'total_hours' => 'required|numeric|min:0',
            'regular_hours' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $employee = User::find($request->employee_id);
        $hourlyRate = $employee->hourly_rate ?? 0;

        $regularPay = $request->regular_hours * $hourlyRate;
        $overtimePay = ($request->overtime_hours ?? 0) * ($hourlyRate * 1.5); // Assuming 1.5x for overtime
        $deductions = $request->deductions ?? 0;
        $totalPay = $regularPay + $overtimePay - $deductions;

        PayrollReport::create([
            'employee_id' => $request->employee_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'total_hours' => $request->total_hours,
            'regular_hours' => $request->regular_hours,
            'overtime_hours' => $request->overtime_hours ?? 0,
            'hourly_rate' => $hourlyRate,
            'regular_pay' => $regularPay,
            'overtime_pay' => $overtimePay,
            'deductions' => $deductions,
            'total_pay' => $totalPay,
            'days_worked' => round($request->total_hours / 8), // Assuming 8 hours per day
            'attendance_rate' => 100, // This would be calculated based on actual attendance
            'status' => 'generated',
            'payment_status' => 'pending',
            'generated_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll report created successfully.');
    }

    public function showPayroll(PayrollReport $payrollReport)
    {
        $payrollReport->load(['employee', 'generator']);
        return view('admin.payroll.show', compact('payrollReport'));
    }

    public function editPayroll(PayrollReport $payrollReport)
    {
        $employees = User::employees()->active()->get();
        return view('admin.payroll.form', compact('payrollReport', 'employees'));
    }

    public function updatePayroll(Request $request, PayrollReport $payrollReport)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'total_hours' => 'required|numeric|min:0',
            'regular_hours' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid',
            'notes' => 'nullable|string',
        ]);

        $employee = User::find($request->employee_id);
        $hourlyRate = $employee->hourly_rate ?? 0;

        $regularPay = $request->regular_hours * $hourlyRate;
        $overtimePay = ($request->overtime_hours ?? 0) * ($hourlyRate * 1.5);
        $deductions = $request->deductions ?? 0;
        $totalPay = $regularPay + $overtimePay - $deductions;

        $payrollReport->update([
            'employee_id' => $request->employee_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'total_hours' => $request->total_hours,
            'regular_hours' => $request->regular_hours,
            'overtime_hours' => $request->overtime_hours ?? 0,
            'hourly_rate' => $hourlyRate,
            'regular_pay' => $regularPay,
            'overtime_pay' => $overtimePay,
            'deductions' => $deductions,
            'total_pay' => $totalPay,
            'days_worked' => round($request->total_hours / 8),
            'payment_status' => $request->payment_status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll report updated successfully.');
    }

    public function destroyPayroll(PayrollReport $payrollReport)
    {
        $payrollReport->delete();
        return redirect()->route('admin.payroll.index')->with('success', 'Payroll report deleted successfully.');
    }

    public function reports(Request $request)
    {
        $reportType = $request->get('type', 'attendance');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $employeeId = $request->get('employee_id');

        $data = [];

        switch ($reportType) {
            case 'attendance':
                $data = $this->getAttendanceReport($startDate, $endDate, $employeeId);
                break;
            case 'payroll':
                $data = $this->getPayrollReport($startDate, $endDate, $employeeId);
                break;
            case 'shifts':
                $data = $this->getShiftReport($startDate, $endDate, $employeeId);
                break;
            case 'performance':
                $data = $this->getPerformanceReport($startDate, $endDate, $employeeId);
                break;
            default:
                $data = $this->getAttendanceReport($startDate, $endDate, $employeeId);
        }

        $employees = User::employees()->active()->get();

        return view('admin.reports.index', array_merge($data, [
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'employeeId' => $employeeId,
            'employees' => $employees,
        ]));
    }

    private function getAttendanceReport($startDate, $endDate, $employeeId = null)
    {
        $query = AttendanceLog::whereBetween('attendance_date', [$startDate, $endDate]);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $attendanceLogs = $query->with(['employee', 'shift'])->get();

        // Monthly attendance data for chart
        $monthlyAttendance = AttendanceLog::selectRaw('MONTH(attendance_date) as month, COUNT(*) as count')
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Status distribution
        $statusDistribution = $attendanceLogs->groupBy('status')->map->count();

        // Employee attendance summary
        $employeeSummary = $attendanceLogs->groupBy('employee_id')->map(function ($logs, $empId) {
            $employee = $logs->first()->employee;
            return [
                'employee' => $employee,
                'total_days' => $logs->count(),
                'present_days' => $logs->where('status', 'present')->count(),
                'absent_days' => $logs->where('status', 'absent')->count(),
                'late_days' => $logs->where('status', 'late')->count(),
                'total_hours' => $logs->sum('total_hours'),
                'attendance_rate' => $logs->count() > 0 ? round(($logs->where('status', 'present')->count() / $logs->count()) * 100, 1) : 0,
            ];
        });

        return [
            'attendanceLogs' => $attendanceLogs,
            'monthlyAttendance' => $monthlyAttendance,
            'statusDistribution' => $statusDistribution,
            'employeeSummary' => $employeeSummary,
        ];
    }

    private function getPayrollReport($startDate, $endDate, $employeeId = null)
    {
        $query = PayrollReport::whereBetween('period_start', [$startDate, $endDate])
            ->orWhereBetween('period_end', [$startDate, $endDate]);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $payrollReports = $query->with(['employee', 'generator'])->get();

        // Payroll summary
        $payrollSummary = [
            'total_reports' => $payrollReports->count(),
            'total_payroll' => $payrollReports->sum('total_pay'),
            'average_payroll' => $payrollReports->avg('total_pay'),
            'paid_reports' => $payrollReports->where('payment_status', 'paid')->count(),
            'pending_reports' => $payrollReports->where('payment_status', 'pending')->count(),
        ];

        // Monthly payroll data for chart
        $monthlyPayroll = $payrollReports->groupBy(function ($report) {
            return $report->period_start->format('M Y');
        })->map->sum('total_pay');

        return [
            'payrollReports' => $payrollReports,
            'payrollSummary' => $payrollSummary,
            'monthlyPayroll' => $monthlyPayroll,
        ];
    }

    private function getShiftReport($startDate, $endDate, $employeeId = null)
    {
        $query = EmployeeShift::whereBetween('shift_date', [$startDate, $endDate]);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $employeeShifts = $query->with(['employee', 'shift'])->get();

        // Shift distribution
        $shiftDistribution = $employeeShifts->groupBy('shift.shift_name')->map->count();

        // Status distribution
        $shiftStatusDistribution = $employeeShifts->groupBy('status')->map->count();

        // Employee shift summary
        $employeeShiftSummary = $employeeShifts->groupBy('employee_id')->map(function ($shifts, $empId) {
            $employee = $shifts->first()->employee;
            return [
                'employee' => $employee,
                'total_shifts' => $shifts->count(),
                'accepted_shifts' => $shifts->where('status', 'accepted')->count(),
                'rejected_shifts' => $shifts->where('status', 'rejected')->count(),
                'pending_shifts' => $shifts->where('status', 'pending')->count(),
            ];
        });

        return [
            'employeeShifts' => $employeeShifts,
            'shiftDistribution' => $shiftDistribution,
            'shiftStatusDistribution' => $shiftStatusDistribution,
            'employeeShiftSummary' => $employeeShiftSummary,
        ];
    }

    private function getPerformanceReport($startDate, $endDate, $employeeId = null)
    {
        $query = AttendanceLog::whereBetween('attendance_date', [$startDate, $endDate]);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $attendanceLogs = $query->with(['employee', 'shift'])->get();

        // Performance metrics
        $performanceMetrics = $attendanceLogs->groupBy('employee_id')->map(function ($logs, $empId) {
            $employee = $logs->first()->employee;
            $totalDays = $logs->count();
            $presentDays = $logs->where('status', 'present')->count();
            $lateDays = $logs->where('status', 'late')->count();
            $absentDays = $logs->where('status', 'absent')->count();
            $totalHours = $logs->sum('total_hours');
            $overtimeHours = $logs->sum('overtime_hours');

            return [
                'employee' => $employee,
                'attendance_rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0,
                'punctuality_rate' => $totalDays > 0 ? round((($presentDays - $lateDays) / $totalDays) * 100, 1) : 0,
                'total_hours' => $totalHours,
                'average_hours_per_day' => $totalDays > 0 ? round($totalHours / $totalDays, 2) : 0,
                'overtime_hours' => $overtimeHours,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
            ];
        });

        // Top performers
        $topPerformers = $performanceMetrics->sortByDesc('attendance_rate')->take(10);

        // Department performance
        $departmentPerformance = $performanceMetrics->groupBy(function ($metric) {
            return $metric['employee']->department ?? 'No Department';
        })->map(function ($metrics) {
            return [
                'total_employees' => $metrics->count(),
                'average_attendance_rate' => round($metrics->avg('attendance_rate'), 1),
                'average_punctuality_rate' => round($metrics->avg('punctuality_rate'), 1),
                'total_hours' => $metrics->sum('total_hours'),
            ];
        });

        return [
            'performanceMetrics' => $performanceMetrics,
            'topPerformers' => $topPerformers,
            'departmentPerformance' => $departmentPerformance,
        ];
    }

    public function settings()
    {
        // Get current settings from config or database
        $settings = [
            'company_name' => config('app.name', 'EMS'),
            'default_working_hours' => config('app.default_working_hours', 8),
            'timezone' => config('app.timezone', 'UTC'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'default_working_hours' => 'required|integer|min:1|max:24',
            'timezone' => 'required|string|timezone',
        ]);

        // Update .env file for persistence
        $this->updateEnvironmentFile([
            'APP_NAME' => $request->company_name,
            'APP_DEFAULT_WORKING_HOURS' => $request->default_working_hours,
            'APP_TIMEZONE' => $request->timezone,
        ]);

        // Update config values for immediate effect
        config(['app.name' => $request->company_name]);
        config(['app.default_working_hours' => $request->default_working_hours]);
        config(['app.timezone' => $request->timezone]);

        return redirect()->back()->with('success', 'Settings updated successfully. Please run "php artisan config:cache" in your terminal to apply changes globally.');
    }

    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('backups/' . $filename);

            // Ensure backup directory exists
            if (!file_exists(storage_path('backups'))) {
                mkdir(storage_path('backups'), 0755, true);
            }

            // Use mysqldump command (adjust for your database)
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                return response()->download($path)->deleteFileAfterSend();
            } else {
                return redirect()->back()->with('error', 'Database backup failed.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database backup failed: ' . $e->getMessage());
        }
    }

    public function exportData()
    {
        try {
            $data = [
                'employees' => User::employees()->get(),
                'shifts' => Shift::all(),
                'attendance_logs' => AttendanceLog::all(),
                'employee_shifts' => EmployeeShift::all(),
            ];

            $filename = 'export_' . date('Y-m-d_H-i-s') . '.json';
            $path = storage_path('exports/' . $filename);

            // Ensure export directory exists
            if (!file_exists(storage_path('exports'))) {
                mkdir(storage_path('exports'), 0755, true);
            }

            file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));

            return response()->download($path)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data export failed: ' . $e->getMessage());
        }
    }

    public function clearLogs()
    {
        try {
            // Clear Laravel logs
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
            }

            // Clear audit logs from database
            DB::table('audit_logs')->delete();

            return redirect()->back()->with('success', 'Logs cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear logs: ' . $e->getMessage());
        }
    }

    private function updateEnvironmentFile(array $data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // Escape special characters in value
            $escapedValue = $this->escapeEnvValue($value);
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$escapedValue}";
            $envContent = preg_replace($pattern, $replacement, $envContent);
        }

        file_put_contents($envFile, $envContent);
    }

    private function escapeEnvValue($value)
    {
        // If value contains spaces or special characters, wrap in quotes
        if (preg_match('/\s/', $value) || strpos($value, '"') !== false) {
            return '"' . addslashes($value) . '"';
        }
        return $value;
    }

    public function exportPayroll(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $userType = $request->get('user_type');

        $filename = 'payroll_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($employeeId, $userType) {
            $handle = fopen('php://output', 'w');

            // Write CSV Header
            fputcsv($handle, ['User Type', 'Name', 'Email', 'Shifts Count', 'Total Hours', 'Hourly Rate', 'Total Pay']);

            $users = collect();

            // Get employees if not filtering by client only
            if (!$userType || $userType === 'employee') {
                $employees = User::employees()->active()
                    ->when($employeeId, fn($q) => $q->where('id', $employeeId))
                    ->with(['employeeShifts' => function ($query) {
                        $query->where('status', 'accepted')->with('shift');
                    }])
                    ->get()
                    ->map(function ($employee) {
                        $totalHours = 0;
                        $totalPay = 0;

                        foreach ($employee->employeeShifts as $shiftAssignment) {
                            if ($shiftAssignment->shift) {
                                $startTime = \Carbon\Carbon::parse($shiftAssignment->shift->start_time);
                                $endTime = \Carbon\Carbon::parse($shiftAssignment->shift->end_time);
                                if ($endTime->lessThan($startTime)) {
                                    $endTime->addDay();
                                }
                                $hours = $endTime->diffInHours($startTime);
                                $totalHours += $hours;
                                $totalPay += $hours * $employee->hourly_rate;
                            }
                        }

                        return [
                            'type' => 'Employee',
                            'name' => $employee->name,
                            'email' => $employee->email,
                            'shifts_count' => $employee->employeeShifts->count(),
                            'calculated_hours' => $totalHours,
                            'hourly_rate' => $employee->hourly_rate,
                            'calculated_pay' => $totalPay,
                        ];
                    })
                    ->filter(fn($employee) => $employee['shifts_count'] > 0);

                $users = $users->merge($employees);
            }

            // Get clients if not filtering by employee only
            if (!$userType || $userType === 'client') {
                $clients = User::clients()->active()
                    ->when($employeeId, fn($q) => $q->where('id', $employeeId))
                    ->with(['employeeShifts' => function ($query) {
                        $query->where('status', 'accepted')->with('shift');
                    }])
                    ->get()
                    ->map(function ($client) {
                        $totalHours = 0;
                        $totalPay = 0;

                        foreach ($client->employeeShifts as $shiftAssignment) {
                            if ($shiftAssignment->shift) {
                                $startTime = \Carbon\Carbon::parse($shiftAssignment->shift->start_time);
                                $endTime = \Carbon\Carbon::parse($shiftAssignment->shift->end_time);
                                if ($endTime->lessThan($startTime)) {
                                    $endTime->addDay();
                                }
                                $hours = $endTime->diffInHours($startTime);
                                $totalHours += $hours;
                                $totalPay += $hours * $client->hourly_rate;
                            }
                        }

                        return [
                            'type' => 'Client',
                            'name' => $client->name,
                            'email' => $client->email,
                            'shifts_count' => $client->employeeShifts->count(),
                            'calculated_hours' => $totalHours,
                            'hourly_rate' => $client->hourly_rate,
                            'calculated_pay' => $totalPay,
                        ];
                    })
                    ->filter(fn($client) => $client['shifts_count'] > 0);

                $users = $users->merge($clients);
            }

            // Write user rows
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user['type'],
                    $user['name'],
                    $user['email'],
                    $user['shifts_count'],
                    $user['calculated_hours'] . 'h',
                    '$' . number_format($user['hourly_rate'], 2),
                    '$' . number_format($user['calculated_pay'], 2),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
