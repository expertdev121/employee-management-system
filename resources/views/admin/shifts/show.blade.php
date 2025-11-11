@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #3b82f6;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
    }

    .page-header {
        margin-bottom: 2rem;
        animation: slideDown 0.5s ease-out;
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .page-title i {
        font-size: 1.5rem;
        color: var(--success);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .action-buttons-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-warning-custom {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
    }

    .btn-warning-custom:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(245, 158, 11, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary-custom:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #1f2937;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .dashboard-card .card-body {
        padding: 2rem;
    }

    .info-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--primary);
        font-size: 1.125rem;
    }

    .info-item {
        display: flex;
        padding: 0.875rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 140px;
        font-size: 0.9375rem;
    }

    .info-value {
        color: #1f2937;
        font-weight: 500;
        flex: 1;
    }

    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .employee-card {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .employee-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .employee-card-item {
        display: flex;
        margin-bottom: 0.625rem;
        font-size: 0.9375rem;
    }

    .employee-card-item:last-child {
        margin-bottom: 0;
    }

    .employee-card-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 120px;
    }

    .employee-card-value {
        color: #1f2937;
        font-weight: 500;
    }

    .attendance-card {
        background: #f9fafb;
        border-left: 4px solid #3b82f6;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .attendance-card:hover {
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .attendance-card-item {
        display: flex;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .attendance-card-item:last-child {
        margin-bottom: 0;
    }

    .attendance-card-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 100px;
    }

    .attendance-card-value {
        color: #1f2937;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .action-buttons-group {
            flex-direction: column;
        }

        .btn-custom {
            justify-content: center;
        }

        .info-item {
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            min-width: auto;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-clock"></i>
            <h2>Shift Details</h2>
        </div>
        <div class="action-buttons-group">
            @if($shift->status === 'active')
            <button type="button" class="btn-custom btn-primary-custom" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal">
                <i class="fas fa-user-plus"></i>
                <span>Assign Employee</span>
            </button>
            @endif
            <a href="{{ route('admin.shifts.edit', $shift) }}" class="btn-custom btn-warning-custom">
                <i class="fas fa-edit"></i>
                <span>Edit Shift</span>
            </a>
            <a href="{{ route('admin.shifts.index') }}" class="btn-custom btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Shift Information
                    </h4>
                    <div class="info-item">
                        <span class="info-label">Shift Name:</span>
                        <span class="info-value">{{ $shift->shift_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Shift Type:</span>
                        <span class="info-value">
                            <span class="badge-custom badge-primary">{{ ucfirst($shift->shift_type) }}</span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Start Time:</span>
                        <span class="info-value">
                            <i class="fas fa-clock" style="color: #10b981; margin-right: 0.375rem;"></i>
                            {{ $shift->start_time }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">End Time:</span>
                        <span class="info-value">
                            <i class="fas fa-clock" style="color: #ef4444; margin-right: 0.375rem;"></i>
                            {{ $shift->end_time }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Max Capacity:</span>
                        <span class="info-value">
                            <i class="fas fa-users" style="color: #3b82f6; margin-right: 0.375rem;"></i>
                            {{ $shift->max_capacity }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Location:</span>
                        <span class="info-value">
                            @if($shift->location)
                                <i class="fas fa-map-marker-alt" style="color: #f59e0b; margin-right: 0.375rem;"></i>
                                {{ $shift->location }}
                            @else
                                <span style="color: #9ca3af;">N/A</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="badge-custom badge-{{ $shift->status === 'active' ? 'success' : 'secondary' }}">
                                @if($shift->status === 'active')
                                    <i class="fas fa-check" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                @endif
                                {{ ucfirst($shift->status) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Description:</span>
                        <span class="info-value">{{ $shift->description ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created:</span>
                        <span class="info-value">
                            <i class="fas fa-calendar" style="color: #6b7280; margin-right: 0.375rem;"></i>
                            {{ $shift->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-users"></i>
                        Assigned Employees
                    </h4>
                    @if($shift->employeeShifts->count() > 0)
                        @foreach($shift->employeeShifts as $assignment)
                            @if($assignment->employee)
                                <div class="employee-card">
                                    <div class="employee-card-item">
                                        <span class="employee-card-label">Name:</span>
                                        <span class="employee-card-value">{{ $assignment->employee->name }}</span>
                                    </div>
                                    <div class="employee-card-item">
                                        <span class="employee-card-label">Email:</span>
                                        <span class="employee-card-value">{{ $assignment->employee->email }}</span>
                                    </div>
                                    <div class="employee-card-item">
                                        <span class="employee-card-label">Status:</span>
                                        <span class="employee-card-value">
                                            <span class="badge-custom badge-{{ $assignment->status === 'assigned' ? 'success' : ($assignment->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </span>
                                    </div>
                                    @if($assignment->shift_date)
                                        <div class="employee-card-item">
                                            <span class="employee-card-label">Assigned Date:</span>
                                            <span class="employee-card-value">{{ $assignment->shift_date->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <div class="employee-card-item">
                                            <span class="employee-card-label">Assignment Type:</span>
                                            <span class="employee-card-value">Recurring Shift</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>No employees assigned to this shift</p>
                        </div>
                    @endif
                </div>

                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-calendar-check"></i>
                        Recent Attendance
                    </h4>
                    @php
                        $recentAttendance = $shift->employeeShifts()
                            ->with(['employee.attendanceLogs' => function($query) {
                                $query->latest()->take(3);
                            }])
                            ->get()
                            ->pluck('employee.attendanceLogs')
                            ->flatten()
                            ->filter(function($attendance) {
                                return $attendance && $attendance->employee;
                            })
                            ->sortByDesc('attendance_date')
                            ->take(5);
                    @endphp
                    @if($recentAttendance->count() > 0)
                        @foreach($recentAttendance as $attendance)
                            @if($attendance->employee)
                                <div class="attendance-card">
                                    <div class="attendance-card-item">
                                        <span class="attendance-card-label">Employee:</span>
                                        <span class="attendance-card-value">{{ $attendance->employee->name }}</span>
                                    </div>
                                    <div class="attendance-card-item">
                                        <span class="attendance-card-label">Date:</span>
                                        <span class="attendance-card-value">{{ $attendance->attendance_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="attendance-card-item">
                                        <span class="attendance-card-label">Status:</span>
                                        <span class="attendance-card-value">
                                            <span class="badge-custom badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>No recent attendance records</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Employee Modal -->
<div class="modal fade" id="assignEmployeeModal" tabindex="-1" aria-labelledby="assignEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignEmployeeModalLabel">Assign Employee to Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.shifts.assign', $shift) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Select Employee <span class="text-danger">*</span></label>
                        <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                            <option value="">Choose an employee...</option>
                            @php
                                $availableEmployees = \App\Models\User::employees()->active()->get();
                            @endphp
                            @foreach($availableEmployees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->email }})</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3" placeholder="Optional notes about this assignment">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
