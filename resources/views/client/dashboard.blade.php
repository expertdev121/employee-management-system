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

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
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

    /* Shift Items */
    .shift-item {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .shift-item:hover {
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.15);
        transform: translateX(4px);
    }

    .shift-item.purple {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border-left-color: var(--secondary);
    }

    .shift-item-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .shift-item-detail {
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .shift-item-detail i {
        width: 16px;
        color: var(--primary);
    }

    .shift-item.purple .shift-item-detail i {
        color: var(--secondary);
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

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Quick Action Buttons */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        color: #1f2937;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover {
        border-color: var(--primary);
        background: #eff6ff;
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .quick-action-btn i {
        font-size: 1.25rem;
    }

    /* Start Shift Button */
    .start-shift-btn {
        border: none;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .start-shift-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
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

    /* View All Link */
    .view-all-link {
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--primary);
        transition: color 0.2s ease;
    }

    .view-all-link:hover {
        color: var(--primary-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="page-header">
    <h1>Welcome back, {{ Auth::user()->name }}! 👋</h1>
    <p>Here's what's happening with your shifts today.</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-card-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-card-value">{{ $todayShifts->count() }}</div>
        <div class="stat-card-label">Today's Shifts</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-card-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-card-value">{{ number_format($thisMonthHours, 1) }}h</div>
        <div class="stat-card-label">This Month Hours</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-card-value">{{ $upcomingShifts->count() }}</div>
        <div class="stat-card-label">Upcoming Shifts</div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="row">
    <!-- Today's Shifts -->
    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5>Today's Shifts</h5>
                <span class="badge-custom badge-info">{{ $todayShifts->count() }} Active</span>
            </div>
            <div class="dashboard-card-body">
                @if($todayShifts->count() > 0)
                    @foreach($todayShifts as $shift)
                        <div class="shift-item">
                            <div class="shift-item-title">{{ $shift->shift->shift_name }}</div>
                            <div class="shift-item-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</span>
                            </div>
                            <div class="shift-item-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $shift->shift->location }}</span>
                            </div>
                            @if($shift->status == 'accepted')
                                <div class="mt-2">
                                    <span class="badge-custom badge-success">Accepted - Hours will be added automatically</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📅</div>
                        <div class="empty-state-text">No shifts scheduled for today</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Shifts -->
    <div class="col-lg-6">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5>Upcoming Shifts</h5>
                <span class="badge-custom" style="background: #f3f4f6; color: #374151;">Next 7 Days</span>
            </div>
            <div class="dashboard-card-body">
                @if($upcomingShifts->count() > 0)
                    @foreach($upcomingShifts as $shift)
                        <div class="shift-item purple">
                            <div class="shift-item-title">{{ $shift->shift->shift_name }}</div>
                            <div class="shift-item-detail">
                                <i class="fas fa-calendar"></i>
                                @if($shift->shift_date)
                                    <span>{{ $shift->shift_date->format('M d, Y') }}</span>
                                @else
                                    <span>Recurring</span>
                                @endif
                            </div>
                            <div class="shift-item-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">🗓️</div>
                        <div class="empty-state-text">No upcoming shifts scheduled</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance -->
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5>Recent Attendance</h5>
        <a href="{{ route('client.attendance.index') }}" class="view-all-link">
            View All <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
        </a>
    </div>
    <div class="dashboard-card-body" style="padding: 0;">
        @if($recentAttendance->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Hours Worked</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAttendance as $attendance)
                            <tr>
                                <td>
                                    <div style="font-weight: 500;">{{ $attendance->attendance_date->format('M d, Y') }}</div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $attendance->attendance_date->format('l') }}</div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td style="font-weight: 600;">
                                    {{ $attendance->total_hours ? number_format($attendance->total_hours, 1) . 'h' : '-' }}
                                </td>
                                <td style="color: #6b7280;">{{ $attendance->notes ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <div class="empty-state-text">No attendance records found</div>
            </div>
        @endif
    </div>
</div>

<!-- Recent Payroll -->
@if($recentPayrolls->count() > 0)
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5>Recent Payroll</h5>
        <a href="#" class="view-all-link">
            View All <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
        </a>
    </div>
    <div class="dashboard-card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Total Hours</th>
                        <th>Total Pay</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPayrolls as $payroll)
                        <tr>
                            <td>
                                <div style="font-weight: 500;">{{ $payroll->period_start->format('M d') }} - {{ $payroll->period_end->format('M d, Y') }}</div>
                            </td>
                            <td style="font-weight: 600;">
                                {{ number_format($payroll->total_hours, 1) }}h
                            </td>
                            <td style="font-weight: 600; color: var(--success);">
                                ${{ number_format($payroll->total_pay, 2) }}
                            </td>
                            <td>
                                <span class="badge-custom badge-{{ $payroll->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($payroll->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5>Quick Actions</h5>
    </div>
    <div class="dashboard-card-body">
        <div class="quick-actions-grid">
            <a href="{{ route('client.attendance.index') }}" class="quick-action-btn">
                <i class="fas fa-clock"></i>
                <span>View Attendance</span>
            </a>
            <a href="{{ route('client.shifts.index') }}" class="quick-action-btn">
                <i class="fas fa-calendar"></i>
                <span>View Shifts</span>
            </a>
            {{-- <a href="{{ route('client.requests.index') }}" class="quick-action-btn">
                <i class="fas fa-list"></i>
                <span>View Requests</span>
            </a> --}}
            <a href="{{ route('client.profile.edit') }}" class="quick-action-btn">
                <i class="fas fa-user"></i>
                <span>Edit Profile</span>
            </a>
        </div>
    </div>
</div>


@endsection
