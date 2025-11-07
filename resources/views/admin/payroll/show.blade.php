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

    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .detail-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .detail-card-header h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .detail-card-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .calculation-breakdown {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .calculation-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .calculation-item:last-child {
        border-bottom: none;
        font-weight: 700;
        color: #10b981;
        font-size: 1.1rem;
    }

    .calculation-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .calculation-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
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

    .btn-secondary {
        background: #6b7280;
        color: white;
        border-color: #6b7280;
    }

    .btn-secondary:hover {
        background: #4b5563;
        border-color: #4b5563;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #eff6ff;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .employee-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .employee-details h6 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .employee-details p {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Payroll Report Details 💰</h1>
            <p>View detailed payroll information and calculations.</p>
        </div>
        <div>
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Payroll
            </a>
        </div>
    </div>
</div>

<!-- Employee Information -->
<div class="employee-info">
    <div class="employee-avatar">
        {{ substr($payrollReport->employee->name, 0, 1) }}
    </div>
    <div class="employee-details">
        <h6>{{ $payrollReport->employee->name }}</h6>
        <p>{{ $payrollReport->employee->email }}</p>
        <p>Employee ID: {{ $payrollReport->employee->id }}</p>
    </div>
</div>

<!-- Payroll Details -->
<div class="detail-card">
    <div class="detail-card-header">
        <h5>Payroll Information</h5>
        <span class="status-badge status-{{ $payrollReport->payment_status }}">
            {{ ucfirst($payrollReport->payment_status) }}
        </span>
    </div>
    <div class="detail-card-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Pay Period</div>
                <div class="info-value">{{ $payrollReport->period_start->format('M d, Y') }} - {{ $payrollReport->period_end->format('M d, Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Hours</div>
                <div class="info-value">{{ $payrollReport->total_hours }} hours</div>
            </div>
            <div class="info-item">
                <div class="info-label">Regular Hours</div>
                <div class="info-value">{{ $payrollReport->regular_hours }} hours</div>
            </div>
            <div class="info-item">
                <div class="info-label">Overtime Hours</div>
                <div class="info-value">{{ $payrollReport->overtime_hours }} hours</div>
            </div>
            <div class="info-item">
                <div class="info-label">Hourly Rate</div>
                <div class="info-value">${{ number_format($payrollReport->hourly_rate, 2) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Days Worked</div>
                <div class="info-value">{{ $payrollReport->days_worked }} days</div>
            </div>
            <div class="info-item">
                <div class="info-label">Attendance Rate</div>
                <div class="info-value">{{ $payrollReport->attendance_rate }}%</div>
            </div>
            <div class="info-item">
                <div class="info-label">Generated Date</div>
                <div class="info-value">{{ $payrollReport->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Pay Calculation Breakdown -->
<div class="detail-card">
    <div class="detail-card-header">
        <h5>Pay Calculation Breakdown</h5>
    </div>
    <div class="detail-card-body">
        <div class="calculation-breakdown">
            <div class="calculation-item">
                <span class="calculation-label">Hourly Rate:</span>
                <span class="calculation-value">${{ number_format($payrollReport->hourly_rate, 2) }}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Regular Pay ({{ $payrollReport->regular_hours }}h):</span>
                <span class="calculation-value">${{ number_format($payrollReport->regular_pay, 2) }}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Overtime Pay ({{ $payrollReport->overtime_hours }}h @ 1.5x):</span>
                <span class="calculation-value">${{ number_format($payrollReport->overtime_pay, 2) }}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Gross Pay:</span>
                <span class="calculation-value">${{ number_format($payrollReport->regular_pay + $payrollReport->overtime_pay, 2) }}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Deductions:</span>
                <span class="calculation-value">-${{ number_format($payrollReport->deductions, 2) }}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Net Pay:</span>
                <span class="calculation-value">${{ number_format($payrollReport->total_pay, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Additional Information -->
@if($payrollReport->notes)
<div class="detail-card">
    <div class="detail-card-header">
        <h5>Notes</h5>
    </div>
    <div class="detail-card-body">
        <p style="margin: 0; color: #4b5563; line-height: 1.6;">{{ $payrollReport->notes }}</p>
    </div>
</div>
@endif

<!-- Generation Information -->
<div class="detail-card">
    <div class="detail-card-header">
        <h5>Report Information</h5>
    </div>
    <div class="detail-card-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Generated By</div>
                <div class="info-value">{{ $payrollReport->generator->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Generated Date</div>
                <div class="info-value">{{ $payrollReport->created_at->format('M d, Y \a\t g:i A') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $payrollReport->updated_at->format('M d, Y \a\t g:i A') }}</div>
            </div>
            @if($payrollReport->paid_at)
            <div class="info-item">
                <div class="info-label">Paid Date</div>
                <div class="info-value">{{ $payrollReport->paid_at->format('M d, Y \a\t g:i A') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <a href="{{ route('admin.payroll.edit', $payrollReport) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        Edit Report
    </a>
    <button class="btn btn-danger" onclick="deletePayroll({{ $payrollReport->id }}, '{{ $payrollReport->employee->name }}')">
        <i class="fas fa-trash"></i>
        Delete Report
    </button>
    <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">
        <i class="fas fa-list"></i>
        Back to List
    </a>
</div>

@endsection
