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
        color: var(--primary);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .dashboard-card .card-body {
        padding: 0;
    }

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

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .employee-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .employee-name {
        font-weight: 600;
        color: #1f2937;
    }

    .employee-id {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .date-main {
        font-weight: 500;
        color: #1f2937;
    }

    .date-day {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .time-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .time-cell i {
        color: var(--primary);
        font-size: 0.875rem;
    }

    .time-cell.logout i {
        color: #ef4444;
    }

    .hours-cell {
        font-weight: 700;
        color: #1f2937;
        font-size: 1rem;
    }

    .hours-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        background: #eff6ff;
        color: #1e40af;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.875rem;
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

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 2px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action-view {
        background: white;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    .btn-action-view:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #1e40af;
        transform: translateY(-1px);
    }

    .btn-action-edit {
        background: white;
        border-color: #fed7aa;
        color: #ea580c;
    }

    .btn-action-edit:hover {
        background: #fff7ed;
        border-color: #ea580c;
        color: #c2410c;
        transform: translateY(-1px);
    }

    .btn-action-delete {
        background: white;
        border-color: #fecaca;
        color: #dc2626;
    }

    .btn-action-delete:hover {
        background: #fef2f2;
        border-color: #dc2626;
        color: #991b1b;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
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

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add {
            justify-content: center;
        }

        .custom-table {
            min-width: 700px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-calendar-check"></i>
            <h2>Attendance Management</h2>
        </div>
        <a href="{{ route('admin.attendance.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            <span>Add Manual Entry</span>
        </a>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Total Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceLogs as $log)
                    <tr>
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    @if($log->employee)
                                        {{ strtoupper(substr($log->employee->name, 0, 1)) }}
                                    @else
                                        ?
                                    @endif
                                </div>
                                <div class="employee-details">
                                    <span class="employee-name">
                                        @if($log->employee)
                                            {{ $log->employee->name }}
                                        @else
                                            Unknown Employee
                                        @endif
                                    </span>
                                    <span class="employee-id">
                                        @if($log->employee)
                                            {{ $log->employee->email }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="date-cell">
                                <span class="date-main">{{ $log->attendance_date->format('M d, Y') }}</span>
                                <span class="date-day">{{ $log->attendance_date->format('l') }}</span>
                            </div>
                        </td>
                        <td>
                            @if($log->total_hours)
                            <span class="hours-badge">
                                <i class="fas fa-clock" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                {{ number_format($log->total_hours, 2) }}h
                            </span>
                            @else
                            <span style="color: #9ca3af;">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-custom badge-{{ $log->status === 'present' ? 'success' : ($log->status === 'absent' ? 'danger' : 'warning') }}">
                                @if($log->status === 'present')
                                    <i class="fas fa-check" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                @elseif($log->status === 'absent')
                                    <i class="fas fa-times" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                @else
                                    <i class="fas fa-clock" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                @endif
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.attendance.show', $log) }}" class="btn-action btn-action-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.attendance.edit', $log) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn-action btn-action-delete" onclick="deleteAttendance({{ $log->id }}, '{{ $log->employee ? $log->employee->name : 'Unknown Employee' }} - {{ $log->attendance_date->format('M d, Y') }}')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">📋</div>
                                <div class="empty-state-text">No attendance records found</div>
                                <div class="empty-state-subtext">Start by adding manual attendance entries</div>
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
