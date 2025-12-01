<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shift;
use App\Models\EmployeeShift;
use App\Models\EmployeePayroll;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create additional dummy employees
        $employees = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'phone' => '+1-555-0101',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'status' => 'active',
                'department' => 'Operations',
                'hourly_rate' => 16.50,
                'max_shifts_per_day' => 4,
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@example.com',
                'phone' => '+1-555-0102',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'status' => 'active',
                'department' => 'Operations',
                'hourly_rate' => 17.00,
                'max_shifts_per_day' => 4,
            ],
            [
                'name' => 'Carol Davis',
                'email' => 'carol.davis@example.com',
                'phone' => '+1-555-0103',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'department' => 'Operations',
                'hourly_rate' => 15.75,
                'max_shifts_per_day' => 4,
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => '+1-555-0104',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'department' => 'Operations',
                'hourly_rate' => 18.25,
                'max_shifts_per_day' => 4,
            ],
            [
                'name' => 'Eva Brown',
                'email' => 'eva.brown@example.com',
                'phone' => '+1-555-0105',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'department' => 'Operations',
                'hourly_rate' => 16.00,
                'max_shifts_per_day' => 4,
            ],
        ];

        foreach ($employees as $employee) {
            User::firstOrCreate(
                ['email' => $employee['email']],
                $employee
            );
        }

        // Create dummy clients
        $clients = [
            [
                'name' => 'TechCorp Solutions',
                'email' => 'contact@techcorp.com',
                'phone' => '+1-555-0201',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'department' => 'IT Services',
                'hourly_rate' => 0.00,
                'max_shifts_per_week' => 4,
            ],
            [
                'name' => 'Global Logistics Inc',
                'email' => 'info@globallogistics.com',
                'phone' => '+1-555-0202',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'department' => 'Logistics',
                'hourly_rate' => 0.00,
                'max_shifts_per_week' => 4,
            ],
            [
                'name' => 'RetailMax Stores',
                'email' => 'hr@retailmax.com',
                'phone' => '+1-555-0203',
                'password' => Hash::make('password'),
                'role' => 'client',
                'status' => 'active',
                'department' => 'Retail',
                'hourly_rate' => 0.00,
                'max_shifts_per_week' => 4,
            ],
        ];

        foreach ($clients as $client) {
            User::firstOrCreate(
                ['email' => $client['email']],
                $client
            );
        }

        // Get all employees and shifts
        $employeeUsers = User::where('role', 'employee')->get();
        $shifts = Shift::all();

        // Create employee shift assignments for the past week only (to reduce count)
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        foreach ($employeeUsers as $employee) {
            // Assign 2-3 shifts per employee for the past week
            $assignedShifts = 0;
            $maxShifts = rand(2, 3);

            for ($date = $startDate->copy(); $date->lte($endDate) && $assignedShifts < $maxShifts; $date->addDay()) {
                if ($date->isWeekday()) { // Only assign weekday shifts
                    $dayOfWeek = strtolower($date->format('l'));
                    $shift = $shifts->where('shift_type', $dayOfWeek)->first();

                    if ($shift && rand(0, 1)) { // 50% chance to assign shift
                        EmployeeShift::firstOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'shift_id' => $shift->id,
                                'shift_date' => $date->format('Y-m-d'),
                            ],
                            [
                                'status' => 'accepted',
                                'responded_at' => Carbon::now(),
                            ]
                        );
                        $assignedShifts++;
                    }
                }
            }
        }

        // Create attendance logs and payroll data for completed shifts (past and recent shifts)
        // Include shifts from the last 30 days to ensure we have sufficient data
        $pastShifts = EmployeeShift::where('shift_date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->where('shift_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('status', 'accepted')
            ->with(['employee', 'shift'])
            ->get();

        foreach ($pastShifts as $employeeShift) {
            // Create attendance log with simple 8-hour shift
            $shiftDate = Carbon::parse($employeeShift->shift_date);
            $loginTime = $shiftDate->copy()->setTimeFromTimeString($employeeShift->shift->start_time->format('H:i:s'));
            $logoutTime = $shiftDate->copy()->setTimeFromTimeString($employeeShift->shift->end_time->format('H:i:s'));

            AttendanceLog::firstOrCreate(
                [
                    'employee_id' => $employeeShift->employee_id,
                    'shift_id' => $employeeShift->shift_id,
                    'attendance_date' => $employeeShift->shift_date,
                ],
                [
                    'login_time' => $loginTime,
                    'logout_time' => $logoutTime,
                    'total_hours_minutes' => 480, // 8 hours
                    'total_hours' => 8.0,
                    'break_duration_minutes' => 0,
                    'status' => 'present',
                    'is_overtime' => false,
                    'overtime_hours' => 0,
                    'notes' => 'Auto-generated attendance',
                    'ip_address' => '127.0.0.1',
                    'is_manual_entry' => false,
                    'approved_by' => 1, // Admin user ID
                ]
            );

            // Create payroll data with 8 hours
            $totalPay = $employeeShift->employee->hourly_rate * 8.0;

            EmployeePayroll::firstOrCreate(
                [
                    'employee_id' => $employeeShift->employee_id,
                    'employee_shift_id' => $employeeShift->id,
                    'shift_date' => $employeeShift->shift_date,
                ],
                [
                    'hourly_rate' => $employeeShift->employee->hourly_rate,
                    'total_hours' => 8.0,
                    'total_pay' => $totalPay,
                    'status' => 'active',
                    'accepted_at' => Carbon::now(),
                ]
            );
        }

        // Create dummy attendance and payroll data for clients
        $clients = User::where('role', 'client')->get();

        foreach ($clients as $client) {
            // Create 2-3 attendance logs for each client
            $attendanceCount = rand(2, 3);
            for ($i = 0; $i < $attendanceCount; $i++) {
                $attendanceDate = Carbon::now()->subDays(rand(1, 7))->format('Y-m-d');

                AttendanceLog::firstOrCreate(
                    [
                        'employee_id' => $client->id,
                        'attendance_date' => $attendanceDate,
                    ],
                    [
                        'login_time' => Carbon::createFromTime(9, 0, 0),
                        'logout_time' => Carbon::createFromTime(17, 0, 0),
                        'total_hours_minutes' => 480, // 8 hours
                        'total_hours' => 8.0,
                        'break_duration_minutes' => 60, // 1 hour break
                        'status' => 'present',
                        'is_overtime' => false,
                        'overtime_hours' => 0,
                        'notes' => 'Client attendance record',
                        'ip_address' => '127.0.0.1',
                        'is_manual_entry' => false,
                        'approved_by' => 1, // Admin user ID
                    ]
                );

                // Create corresponding payroll record for client
                EmployeePayroll::firstOrCreate(
                    [
                        'employee_id' => $client->id,
                        'shift_date' => $attendanceDate,
                    ],
                    [
                        'employee_shift_id' => null, // No shift assignment for clients
                        'hourly_rate' => 20.00, // Default rate for clients
                        'total_hours' => 8.0,
                        'total_pay' => 160.00, // 20.00 * 8.0
                        'status' => 'active',
                        'accepted_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
