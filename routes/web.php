<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        } elseif ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }
    }
    return redirect('/login');
});
Route::get('/home', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        } elseif ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }
    }
    return redirect('/login');
})->name('home');
// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/export', [AdminController::class, 'exportDashboard'])->name('admin.dashboard.export');

    // Employees CRUD
    Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees.index');
    Route::get('/admin/employees/create', [AdminController::class, 'createEmployee'])->name('admin.employees.create');
    Route::post('/admin/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    Route::get('/admin/employees/{employee}', [AdminController::class, 'showEmployee'])->name('admin.employees.show');
    Route::get('/admin/employees/{employee}/edit', [AdminController::class, 'editEmployee'])->name('admin.employees.edit');
    Route::put('/admin/employees/{employee}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
    Route::delete('/admin/employees/{employee}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');

    // Clients CRUD
    Route::get('/admin/clients', [AdminController::class, 'clients'])->name('admin.clients.index');
    Route::get('/admin/clients/create', [AdminController::class, 'createClient'])->name('admin.clients.create');
    Route::post('/admin/clients', [AdminController::class, 'storeClient'])->name('admin.clients.store');
    Route::get('/admin/clients/{client}', [AdminController::class, 'showClient'])->name('admin.clients.show');
    Route::get('/admin/clients/{client}/edit', [AdminController::class, 'editClient'])->name('admin.clients.edit');
    Route::put('/admin/clients/{client}', [AdminController::class, 'updateClient'])->name('admin.clients.update');
    Route::delete('/admin/clients/{client}', [AdminController::class, 'destroyClient'])->name('admin.clients.destroy');

    // Shifts CRUD
    Route::get('/admin/shifts', [AdminController::class, 'shifts'])->name('admin.shifts.index');
    Route::get('/admin/shifts/create', [AdminController::class, 'createShift'])->name('admin.shifts.create');
    Route::post('/admin/shifts', [AdminController::class, 'storeShift'])->name('admin.shifts.store');
    Route::get('/admin/shifts/{shift}', [AdminController::class, 'showShift'])->name('admin.shifts.show');
    Route::get('/admin/shifts/{shift}/edit', [AdminController::class, 'editShift'])->name('admin.shifts.edit');
    Route::put('/admin/shifts/{shift}', [AdminController::class, 'updateShift'])->name('admin.shifts.update');
    Route::delete('/admin/shifts/{shift}', [AdminController::class, 'destroyShift'])->name('admin.shifts.destroy');
    Route::post('/admin/shifts/{shift}/assign', [AdminController::class, 'assignEmployeeToShift'])->name('admin.shifts.assign');
    Route::post('/admin/shifts/{employeeShift}/unassign', [AdminController::class, 'unassignEmployeeFromShift'])->name('admin.shifts.unassign');

    // Employee Attendance CRUD
    Route::get('/admin/employee-attendance', [AdminController::class, 'employeeAttendance'])->name('admin.employee-attendance.index');
    Route::get('/admin/employee-attendance/create', [AdminController::class, 'createEmployeeAttendance'])->name('admin.employee-attendance.create');
    Route::post('/admin/employee-attendance', [AdminController::class, 'storeEmployeeAttendance'])->name('admin.employee-attendance.store');
    Route::get('/admin/employee-attendance/{attendanceLog}', [AdminController::class, 'showEmployeeAttendance'])->name('admin.employee-attendance.show');
    Route::get('/admin/employee-attendance/{attendanceLog}/edit', [AdminController::class, 'editEmployeeAttendance'])->name('admin.employee-attendance.edit');
    Route::put('/admin/employee-attendance/{attendanceLog}', [AdminController::class, 'updateEmployeeAttendance'])->name('admin.employee-attendance.update');
    Route::delete('/admin/employee-attendance/{attendanceLog}', [AdminController::class, 'destroyEmployeeAttendance'])->name('admin.employee-attendance.destroy');

    // Client Attendance CRUD
    Route::get('/admin/client-attendance', [AdminController::class, 'clientAttendance'])->name('admin.client-attendance.index');
    Route::get('/admin/client-attendance/create', [AdminController::class, 'createClientAttendance'])->name('admin.client-attendance.create');
    Route::post('/admin/client-attendance', [AdminController::class, 'storeClientAttendance'])->name('admin.client-attendance.store');
    Route::get('/admin/client-attendance/{attendanceLog}', [AdminController::class, 'showClientAttendance'])->name('admin.client-attendance.show');
    Route::get('/admin/client-attendance/{attendanceLog}/edit', [AdminController::class, 'editClientAttendance'])->name('admin.client-attendance.edit');
    Route::put('/admin/client-attendance/{attendanceLog}', [AdminController::class, 'updateClientAttendance'])->name('admin.client-attendance.update');
    Route::delete('/admin/client-attendance/{attendanceLog}', [AdminController::class, 'destroyClientAttendance'])->name('admin.client-attendance.destroy');

    // Legacy Attendance CRUD (redirect to employee attendance)
    Route::get('/admin/attendance', [AdminController::class, 'attendance'])->name('admin.attendance.index');
    Route::get('/admin/attendance/create', [AdminController::class, 'createAttendance'])->name('admin.attendance.create');
    Route::post('/admin/attendance', [AdminController::class, 'storeAttendance'])->name('admin.attendance.store');
    Route::get('/admin/attendance/{attendanceLog}', [AdminController::class, 'showAttendance'])->name('admin.attendance.show');
    Route::get('/admin/attendance/{attendanceLog}/edit', [AdminController::class, 'editAttendance'])->name('admin.attendance.edit');
    Route::put('/admin/attendance/{attendanceLog}', [AdminController::class, 'updateAttendance'])->name('admin.attendance.update');
    Route::delete('/admin/attendance/{attendanceLog}', [AdminController::class, 'destroyAttendance'])->name('admin.attendance.destroy');

    // Payroll Routes
    Route::get('/admin/payroll', [AdminController::class, 'payroll'])->name('admin.payroll.index');
    Route::get('/admin/payroll/export', [App\Http\Controllers\AdminController::class, 'exportPayroll'])
        ->name('admin.payroll.export');
    Route::get('/admin/payroll/create', [AdminController::class, 'createPayroll'])->name('admin.payroll.create');
    Route::post('/admin/payroll', [AdminController::class, 'storePayroll'])->name('admin.payroll.store');
    Route::get('/admin/payroll/{payrollReport}', [AdminController::class, 'showPayroll'])->name('admin.payroll.show');
    Route::get('/admin/payroll/{payrollReport}/edit', [AdminController::class, 'editPayroll'])->name('admin.payroll.edit');
    Route::put('/admin/payroll/{payrollReport}', [AdminController::class, 'updatePayroll'])->name('admin.payroll.update');
    Route::delete('/admin/payroll/{payrollReport}', [AdminController::class, 'destroyPayroll'])->name('admin.payroll.destroy');


    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports.index');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings.index');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/admin/backup-database', [AdminController::class, 'backupDatabase'])->name('admin.backup.database');
    Route::post('/admin/export-data', [AdminController::class, 'exportData'])->name('admin.export.data');
    Route::post('/admin/clear-logs', [AdminController::class, 'clearLogs'])->name('admin.clear.logs');
});

// Employee Routes
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/employee/shifts', [EmployeeController::class, 'shifts'])->name('employee.shifts.index');
    Route::get('/employee/shifts/{employeeShift}', [EmployeeController::class, 'showShift'])->name('employee.shifts.show');
    Route::post('/employee/shifts/{employeeShift}/accept', [EmployeeController::class, 'acceptShift'])->name('employee.shifts.accept');
    Route::post('/employee/shifts/{employeeShift}/reject', [EmployeeController::class, 'rejectShift'])->name('employee.shifts.reject');
    Route::get('/employee/attendance', [EmployeeController::class, 'attendance'])->name('employee.attendance.index');
    Route::get('/employee/payroll', [EmployeeController::class, 'payroll'])->name('employee.payroll.index');
    Route::get('/employee/requests', [EmployeeController::class, 'requests'])->name('employee.requests.index');
    Route::get('/employee/profile', [EmployeeController::class, 'profile'])->name('employee.profile.edit');
});

// Client Routes
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/client/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/client/shifts', [ClientController::class, 'shifts'])->name('client.shifts.index');
    Route::get('/client/shifts/{employeeShift}', [ClientController::class, 'showShift'])->name('client.shifts.show');
    Route::post('/client/shifts/{employeeShift}/accept', [ClientController::class, 'acceptShift'])->name('client.shifts.accept');
    Route::post('/client/shifts/{employeeShift}/reject', [ClientController::class, 'rejectShift'])->name('client.shifts.reject');
    Route::get('/client/attendance', [ClientController::class, 'attendance'])->name('client.attendance.index');
    Route::get('/client/payroll', [ClientController::class, 'payroll'])->name('client.payroll.index');
    Route::get('/client/requests', [ClientController::class, 'requests'])->name('client.requests.index');
    Route::get('/client/profile', [ClientController::class, 'profile'])->name('client.profile.edit');
});
