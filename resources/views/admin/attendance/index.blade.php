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

<!-- Employee Filter -->
<form method="GET" action="{{ route('admin.attendance.index') }}" class="employee-filter"
    style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">

    <div style="position: relative; width: 320px;">
        <input type="text" id="employeeSearch" name="employee_name" placeholder="🔍 Search employee name or email..."
            value="{{ isset($employeeId) && $employeeId ? ($employees->firstWhere('id', $employeeId)->name ?? '') : '' }}"
            style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.9rem;"
            onkeyup="filterEmployees(this.value)">

        <div id="employeeList"
            style="position: absolute; top: 105%; left: 0; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; max-height: 200px; overflow-y: auto; z-index: 10; display: none;">
            @foreach ($employees as $employee)
                <div class="employee-item" data-id="{{ $employee->id }}" data-name="{{ strtolower($employee->name) }}"
                    data-email="{{ strtolower($employee->email) }}"
                    style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #f3f4f6;"
                    onclick="selectEmployee({{ $employee->id }}, '{{ addslashes($employee->name) }}')">
                    {{ $employee->name }} <br>
                    <span style="font-size: 0.8rem; color: #6b7280;">{{ $employee->email }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <input type="hidden" name="employee_id" id="selectedEmployeeId" value="{{ $employeeId ?? '' }}">

    <!-- Month Filter -->
    <label for="month" style="font-weight: 600; color: #374151;">Month:</label>
    <input type="month" id="month" name="month" value="{{ old('month', $month) }}"
        onchange="this.form.submit()"
        style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #d1d5db;">

    <!-- Start Date Filter -->
    <label for="start_date" style="font-weight: 600; color: #374151;">Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $startDate) }}"
        onchange="this.form.submit()"
        style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #d1d5db;">

    <!-- End Date Filter -->
    <label for="end_date" style="font-weight: 600; color: #374151;">End Date:</label>
    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $endDate) }}"
        onchange="this.form.submit()"
        style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #d1d5db;">

    <button type="submit" class="btn btn-primary" style="padding: 0.55rem 1rem; border-radius: 0.5rem;">
        Apply Filters
    </button>

    @if (request()->hasAny(['employee_id', 'month', 'start_date', 'end_date']))
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary"
            style="background: #6b7280; color: white; border-color: #6b7280; padding: 0.65rem 1rem; border-radius: 0.5rem; font-size: 0.9rem;">
            Clear Filter
        </a>
    @endif
</form>

<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Name/Email</th>
                        <th>Attendance Date</th>
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
                            <span class="badge-custom badge-{{ $log->status == 'present' ? 'success' : ($log->status == 'absent' ? 'danger' : 'warning') }}">
                                @if($log->status == 'present')
                                    <i class="fas fa-check" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                @elseif($log->status == 'absent')
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

<script>
    function filterEmployees(searchTerm) {
        const list = document.getElementById('employeeList');
        const items = list.querySelectorAll('.employee-item');
        searchTerm = searchTerm.toLowerCase().trim();

        if (searchTerm.length === 0) {
            list.style.display = 'none';
            return;
        }

        let found = false;
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            const email = item.getAttribute('data-email');
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = 'block';
                found = true;
            } else {
                item.style.display = 'none';
            }
        });

        list.style.display = found ? 'block' : 'none';
    }

    function selectEmployee(id, name) {
        document.getElementById('selectedEmployeeId').value = id;
        document.getElementById('employeeSearch').value = name;
        document.getElementById('employeeList').style.display = 'none';
        document.querySelector('.employee-filter').submit();
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(event) {
        const list = document.getElementById('employeeList');
        const search = document.getElementById('employeeSearch');
        if (!search.contains(event.target) && !list.contains(event.target)) {
            list.style.display = 'none';
        }
    });
</script>

@endsection
