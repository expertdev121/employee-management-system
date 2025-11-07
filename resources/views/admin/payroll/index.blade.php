@extends('layouts.app')

@section('content')
<style>
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
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #059669); }
    .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

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

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    .btn-success {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .btn-success:hover {
        background: #059669;
        border-color: #059669;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .btn-warning:hover {
        background: #d97706;
        border-color: #d97706;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .btn-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Payroll Management 💰</h1>
            <p>Manage employee payroll reports and payments.</p>
        </div>
        <div>
            <a href="{{ route('admin.payroll.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create Payroll Report
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-card-value">{{ $payrollReports->total() }}</div>
        <div class="stat-card-label">Total Reports</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-value">${{ number_format($payrollReports->sum('total_pay'), 2) }}</div>
        <div class="stat-card-label">Total Payroll Amount</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-card-value">{{ $payrollReports->where('payment_status', 'pending')->count() }}</div>
        <div class="stat-card-label">Pending Payments</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-card-value">{{ $payrollReports->where('payment_status', 'paid')->count() }}</div>
        <div class="stat-card-label">Paid Reports</div>
    </div>
</div>

<!-- Payroll Reports Table -->
<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5>Payroll Reports</h5>
    </div>
    <div class="dashboard-card-body" style="padding: 0;">
        @if($payrollReports->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Period</th>
                            <th>Total Hours</th>
                            <th>Total Pay</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrollReports as $report)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-weight: 600;">
                                            {{ substr($report->employee->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;">{{ $report->employee->name }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $report->employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $report->period_start->format('M d') }} - {{ $report->period_end->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $report->total_hours }}h</div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $report->regular_hours }}h regular</div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #10b981;">${{ number_format($report->total_pay, 2) }}</div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">@ ${{ number_format($report->hourly_rate, 2) }}/hr</div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-{{ $report->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($report->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.payroll.show', $report) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                        <a href="{{ route('admin.payroll.edit', $report) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm" onclick="deletePayroll({{ $report->id }}, '{{ $report->employee->name }}')">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb;">
                {{ $payrollReports->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">💰</div>
                <div class="empty-state-text">No payroll reports found. Create your first payroll report to get started.</div>
            </div>
        @endif
    </div>
</div>

@endsection
