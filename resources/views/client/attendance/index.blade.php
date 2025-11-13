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

    /* Page Header */
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
        color: var(--primary);
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

    /* Back Button */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        border-color: #9ca3af;
        background: #f9fafb;
        color: #1f2937;
        transform: translateX(-2px);
    }

    /* Stats Cards */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        text-align: center;
        transition: box-shadow 0.3s ease;
    }

    .stats-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stats-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .stats-card p {
        color: #6b7280;
        font-weight: 500;
        margin: 0;
        font-size: 0.9375rem;
    }

    /* Dashboard Card */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card .card-body {
        padding: 0;
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
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        color: #1f2937;
        vertical-align: middle;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Table Cell Styles */
    .attendance-date {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .attendance-date-main {
        font-weight: 500;
        color: #1f2937;
    }

    .attendance-date-day {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .time-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .time-display i {
        color: var(--primary);
        font-size: 0.875rem;
    }

    .hours-display {
        font-weight: 600;
        color: #1f2937;
    }

    /* Badges */
    .badge-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 1.125rem;
        color: #6b7280;
        font-weight: 500;
    }

    .empty-state-subtext {
        font-size: 0.9375rem;
        color: #9ca3af;
        margin-top: 0.5rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        border: 2px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-action-danger {
        background: white;
        border-color: #fecaca;
        color: #dc2626;
    }

    .btn-action-danger:hover {
        background: #fef2f2;
        border-color: #dc2626;
        color: #991b1b;
        transform: translateY(-1px);
    }

    .btn-action-primary {
        background: white;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    .btn-action-primary:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #1e40af;
        transform: translateY(-1px);
    }

    .btn-action-warning {
        background: white;
        border-color: #fed7aa;
        color: #ea580c;
    }

    .btn-action-warning:hover {
        background: #fff7ed;
        border-color: #ea580c;
        color: #c2410c;
        transform: translateY(-1px);
    }

    .btn-action-secondary {
        background: #6b7280;
        border-color: #6b7280;
        color: white;
    }

    .btn-action-secondary:hover {
        background: #4b5563;
        border-color: #4b5563;
        color: white;
        transform: translateY(-1px);
    }

    /* Custom Popup Animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .page-title h2 {
            font-size: 1.5rem;
        }

        .btn-back {
            justify-content: center;
        }

        .stats-section {
            grid-template-columns: 1fr;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .custom-table {
            min-width: 800px;
        }

        .custom-table thead th,
        .custom-table tbody td {
            padding: 0.75rem 1rem;
        }

        #attendanceControls {
            flex-direction: column;
            align-items: stretch;
        }

        #attendanceControls button {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-calendar-check"></i>
            <h2>My Attendance</h2>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>
</div>



<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Attendance Date</th>
                        <th>Shift Name</th>
                        <th>Shift Time</th>
                        <th>Hours Worked</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceLogs as $attendance)
                    <tr>
                        <td>
                            <div class="attendance-date">
                                <span class="attendance-date-main">{{ $attendance->attendance_date->format('M d, Y') }}</span>
                                <span class="attendance-date-day">{{ $attendance->attendance_date->format('l') }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #1f2937;">
                                {{ $attendance->shift ? $attendance->shift->shift_name : 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <div class="time-display">
                                <i class="fas fa-clock"></i>
                                @if($attendance->shift)
                                    <span>{{ $attendance->shift->start_time->format('H:i') }} - {{ $attendance->shift->end_time->format('H:i') }}</span>
                                @else
                                    <span>N/A</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="hours-display">{{ number_format($attendance->total_hours, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge-custom badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">📅</div>
                                <div class="empty-state-text">No attendance records found</div>
                                <div class="empty-state-subtext">Your attendance records will appear here</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendanceLogs->hasPages())
        <div class="pagination-wrapper">
            {{ $attendanceLogs->links() }}
        </div>
        @endif
    </div>
</div>


@endsection
