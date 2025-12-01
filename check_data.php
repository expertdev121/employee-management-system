<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\EmployeePayroll;

echo "=== EMPLOYEE DATA FOR PAYROLL AND ATTENDANCE ===\n\n";

$employees = User::where('role', 'employee')->get();

foreach ($employees as $employee) {
    echo "Employee: {$employee->name} ({$employee->email})\n";
    echo "  Department: {$employee->department}\n";
    echo "  Hourly Rate: \${$employee->hourly_rate}\n";

    $attendanceCount = AttendanceLog::where('employee_id', $employee->id)->count();
    $payrollCount = EmployeePayroll::where('employee_id', $employee->id)->count();

    echo "  Attendance Logs: {$attendanceCount}\n";
    echo "  Payroll Records: {$payrollCount}\n";

    // Show sample attendance and payroll data
    $sampleAttendance = AttendanceLog::where('employee_id', $employee->id)->first();
    if ($sampleAttendance) {
        echo "  Sample Attendance: {$sampleAttendance->attendance_date} - {$sampleAttendance->total_hours} hours\n";
    }

    $samplePayroll = EmployeePayroll::where('employee_id', $employee->id)->first();
    if ($samplePayroll) {
        echo "  Sample Payroll: {$samplePayroll->shift_date} - \${$samplePayroll->total_pay} for {$samplePayroll->total_hours} hours\n";
    }

    echo "\n";
}

echo "=== CLIENT DATA FOR PAYROLL AND ATTENDANCE ===\n\n";

$clients = User::where('role', 'client')->get();

foreach ($clients as $client) {
    echo "Client: {$client->name} ({$client->email})\n";
    echo "  Department: {$client->department}\n";
    echo "  Hourly Rate: \${$client->hourly_rate}\n";

    $attendanceCount = AttendanceLog::where('employee_id', $client->id)->count();
    $payrollCount = EmployeePayroll::where('employee_id', $client->id)->count();

    echo "  Attendance Logs: {$attendanceCount}\n";
    echo "  Payroll Records: {$payrollCount}\n";

    // Show sample attendance and payroll data
    $sampleAttendance = AttendanceLog::where('employee_id', $client->id)->first();
    if ($sampleAttendance) {
        echo "  Sample Attendance: {$sampleAttendance->attendance_date} - {$sampleAttendance->total_hours} hours\n";
    }

    $samplePayroll = EmployeePayroll::where('employee_id', $client->id)->first();
    if ($samplePayroll) {
        echo "  Sample Payroll: {$samplePayroll->shift_date} - \${$samplePayroll->total_pay} for {$samplePayroll->total_hours} hours\n";
    }

    echo "\n";
}

echo "=== SUMMARY ===\n";
echo "Total Employees: " . $employees->count() . "\n";
echo "Total Clients: " . $clients->count() . "\n";
echo "Total Attendance Logs: " . AttendanceLog::count() . "\n";
echo "Total Payroll Records: " . EmployeePayroll::count() . "\n";
