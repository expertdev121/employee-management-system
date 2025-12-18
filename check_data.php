<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\EmployeeShift;
use App\Models\Shift;

echo "=== EMPLOYEE SHIFT DATA FOR EMPLOYEE ID 2 ===\n";

$employeeShifts = EmployeeShift::where('employee_id', 2)->with('shift')->get();

if ($employeeShifts->isEmpty()) {
    echo "No shifts found for employee ID 2\n";
} else {
    foreach ($employeeShifts as $employeeShift) {
        echo "Employee Shift ID: {$employeeShift->id}\n";
        echo "  Shift ID: {$employeeShift->shift_id}\n";
        echo "  Shift Date: " . ($employeeShift->shift_date ? $employeeShift->shift_date->format('Y-m-d') : 'Recurring') . "\n";
        echo "  Status: {$employeeShift->status}\n";
        echo "  Responded At: " . ($employeeShift->responded_at ? $employeeShift->responded_at->format('Y-m-d H:i:s') : 'Not responded') . "\n";

        if ($employeeShift->shift) {
            echo "  Shift Name: {$employeeShift->shift->shift_name}\n";
            echo "  Shift Type: {$employeeShift->shift->shift_type}\n";
            echo "  Start Time: {$employeeShift->shift->start_time}\n";
            echo "  End Time: {$employeeShift->shift->end_time}\n";
        }
        echo "\n";
    }
}

echo "=== ALL SHIFTS ===\n";

$shifts = Shift::all();

foreach ($shifts as $shift) {
    echo "Shift ID: {$shift->id}\n";
    echo "  Shift Name: {$shift->shift_name}\n";
    echo "  Shift Type: {$shift->shift_type}\n";
    echo "  Start Time: {$shift->start_time}\n";
    echo "  End Time: {$shift->end_time}\n";
    echo "  Status: {$shift->status}\n\n";
}
