@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #1e40af;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --secondary: #8b5cf6;
    }

    .page-header {
        margin-bottom: 2rem;
        animation: slideDown 0.5s ease-out;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #6b7280;
        font-size: 1rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.blue::before { background: linear-gradient(90deg, #3b82f6, #2563eb); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
    .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #059669); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-card.blue .stat-card-icon { background: #eff6ff; color: #3b82f6; }
    .stat-card.purple .stat-card-icon { background: #f5f3ff; color: #8b5cf6; }
    .stat-card.orange .stat-card-icon { background: #fff7ed; color: #f59e0b; }
    .stat-card.green .stat-card-icon { background: #ecfdf5; color: #10b981; }

    .stat-card-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .stat-card-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-card-trend {
        font-size: 0.75rem;
        color: #10b981;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-card-header h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .dashboard-card-body {
        padding: 1.5rem;
    }

    /* Request Cards */
    .request-card {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .request-card:hover {
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.15);
        transform: translateX(4px);
    }

    .request-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .request-card-title {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }

    .request-card-body {
        margin-bottom: 0.75rem;
    }

    .request-card-detail {
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .request-card-detail i {
        width: 16px;
        color: var(--primary);
    }

    .request-actions {
        display: flex;
        gap: 0.5rem;
    }

    .request-actions .btn {
        flex: 1;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .request-actions .btn-success {
        background: var(--success);
        color: white;
    }

    .request-actions .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .request-actions .btn-danger {
        background: var(--danger);
        color: white;
    }

    .request-actions .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
    }

    .custom-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        color: #1f2937;
    }

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    /* Quick Action Buttons */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.5rem 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        color: #1f2937;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
    }

    .quick-action-btn:hover {
        border-color: var(--primary);
        background: #eff6ff;
        color: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.15);
    }

    .quick-action-icon {
        font-size: 2rem;
    }

    .quick-action-text {
        font-size: 0.9375rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 1rem;
        color: #6b7280;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .page-header h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Admin Dashboard 👨‍💼</h1>
            <p>Welcome back! Here's your office management overview.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard.export') }}" class="btn btn-primary" style="background: var(--primary); border: none; padding: 0.75rem 1.5rem; border-radius: 8px; color: white; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;">
                <i class="fas fa-download"></i>
                Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-card-header">
            <div class="stat-card-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalEmployees }}</div>
        <div class="stat-card-label">Total Employees</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-card-header">
            <div class="stat-card-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $totalShifts }}</div>
        <div class="stat-card-label">Active Shifts</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-card-header">
            <div class="stat-card-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $pendingShiftRequests }}</div>
        <div class="stat-card-label">Pending Requests</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-header">
            <div class="stat-card-icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $todayAttendance }}</div>
        <div class="stat-card-label">Today's Attendance</div>
    </div>

</div>

<!-- Dashboard Content -->
<div class="row">
    <!-- Recent Attendance -->
    <div class="col-lg-12">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5>Recent Attendance</h5>
                <a href="{{ route('admin.attendance.index') }}" style="text-decoration: none; font-size: 0.875rem; font-weight: 500; color: var(--primary);">
                    View All <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                </a>
            </div>
            <div class="dashboard-card-body" style="padding: 0;">
                @if($recentAttendance->count() > 0)
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendance as $attendance)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 600;">
                                                    {{ substr($attendance->employee->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600;">{{ $attendance->employee->name }}</div>
                                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $attendance->employee->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-custom badge-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 500;">{{ $attendance->attendance_date->format('M d, Y') }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $attendance->attendance_date->format('l') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📋</div>
                        <div class="empty-state-text">No recent attendance records found</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Shift Status Overview -->
<div class="row">
    <!-- Assigned Shifts -->
    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5>Assigned Shifts</h5>
                <span class="badge-custom badge-warning">{{ $assignedShifts->count() }} Assigned</span>
            </div>
            <div class="dashboard-card-body">
                @if($assignedShifts->count() > 0)
                    @foreach($assignedShifts as $shift)
                        <div class="request-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left-color: var(--warning);">
                            <div class="request-card-header">
                                <div class="request-card-title">{{ $shift->employee->name }}</div>
                            </div>
                            <div class="request-card-body">
                                <div class="request-card-detail">
                                    <i class="fas fa-briefcase"></i>
                                    <strong>{{ $shift->shift->shift_name }}</strong>
                                </div>
                                <div class="request-card-detail">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $shift->shift_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="request-actions">
                                <a href="{{ route('admin.shifts.show', $shift->shift) }}" class="btn btn-success" style="flex: 1; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📋</div>
                        <div class="empty-state-text">No assigned shifts</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rejected Shifts -->
    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5>Rejected Shifts</h5>
                <span class="badge-custom badge-danger">{{ $rejectedShifts->count() }} Rejected</span>
            </div>
            <div class="dashboard-card-body">
                @if($rejectedShifts->count() > 0)
                    @foreach($rejectedShifts as $shift)
                        <div class="request-card" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left-color: var(--danger);">
                            <div class="request-card-header">
                                <div class="request-card-title">{{ $shift->employee->name }}</div>
                            </div>
                            <div class="request-card-body">
                                <div class="request-card-detail">
                                    <i class="fas fa-briefcase"></i>
                                    <strong>{{ $shift->shift->shift_name }}</strong>
                                 </div>
                                <div class="request-card-detail">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $shift->shift_date->format('M d, Y') }}</span>
                                </div>
                                @if($shift->rejection_reason)
                                <div class="request-card-detail">
                                    <i class="fas fa-comment"></i>
                                    <span style="font-style: italic;">{{ Str::limit($shift->rejection_reason, 50) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="request-actions">
                                <a href="{{ route('admin.shifts.show', $shift->shift) }}" class="btn btn-danger" style="flex: 1; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">❌</div>
                        <div class="empty-state-text">No rejected shifts</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>



<!-- Quick Actions -->
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5>Quick Actions</h5>
    </div>
    <div class="dashboard-card-body">
        <div class="quick-actions-grid">
            <a href="{{ route('admin.employees.index') }}" class="quick-action-btn">
                <div class="quick-action-icon">👥</div>
                <div class="quick-action-text">Manage Employees</div>
            </a>
            <a href="{{ route('admin.shifts.create') }}" class="quick-action-btn">
                <div class="quick-action-icon">📅</div>
                <div class="quick-action-text">Create Shift</div>
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="quick-action-btn">
                <div class="quick-action-icon">✓</div>
                <div class="quick-action-text">Attendance Overview</div>
            </a>
        </div>
    </div>
</div>

@endsection
