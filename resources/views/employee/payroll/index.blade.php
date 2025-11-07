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
    .payroll-period {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .payroll-period-main {
        font-weight: 500;
        color: #1f2937;
    }

    .payroll-period-range {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .amount-display {
        font-weight: 600;
        color: #1f2937;
    }

    .amount-regular {
        color: var(--success);
    }

    .amount-overtime {
        color: var(--warning);
    }

    .amount-total {
        color: var(--primary);
        font-size: 1.1em;
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
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-dollar-sign"></i>
            <h2>My Payroll</h2>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>
</div>

<!-- Current Status Cards -->
<div class="stats-section">
    <div class="stats-card">
        <h3>{{ $payrollReports->where('payment_status', 'paid')->count() }}</h3>
        <p>Paid Payrolls</p>
    </div>
    <div class="stats-card">
        <h3>{{ $payrollReports->where('payment_status', 'pending')->count() }}</h3>
        <p>Pending Payments</p>
    </div>
    <div class="stats-card">
        <h3>${{ number_format($payrollReports->where('payment_status', 'paid')->sum('total_pay'), 2) }}</h3>
        <p>Total Earned</p>
    </div>
    <div class="stats-card">
        <h3>{{ number_format($payrollReports->avg('total_hours'), 1) }}h</h3>
        <p>Average Hours</p>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Hours Worked</th>
                        <th>Pay Rate</th>
                        <th>Total Pay</th>
                        <th>Status</th>
                        <th>Generated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrollReports as $payroll)
                    <tr>
                        <td>
                            <div class="payroll-period">
                                <span class="payroll-period-main">{{ $payroll->period_start->format('M Y') }}</span>
                                <span class="payroll-period-range">{{ $payroll->period_start->format('M d') }} - {{ $payroll->period_end->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1f2937;">
                                {{ number_format($payroll->total_hours, 1) }}h
                            </div>
                        </td>
                        <td>
                            <span class="amount-display">${{ number_format($payroll->total_pay / $payroll->total_hours, 2) }}/hr</span>
                        </td>
                        <td>
                            <span class="amount-display amount-total">${{ number_format($payroll->total_pay, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge-custom badge-{{ $payroll->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($payroll->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem; color: #6b7280;">
                                {{ $payroll->created_at->format('M d, H:i') }}
                                @if($payroll->generator)
                                    <br><small>by {{ $payroll->generator->name }}</small>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">💰</div>
                                <div class="empty-state-text">No payroll records found</div>
                                <div class="empty-state-subtext">Your payroll information will appear here once generated by your administrator</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payrollReports->hasPages())
        <div class="pagination-wrapper">
            {{ $payrollReports->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
