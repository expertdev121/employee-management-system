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

    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .form-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .form-card-header h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        min-height: 100px;
        resize: vertical;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

    .btn-secondary {
        background: #6b7280;
        color: white;
        border-color: #6b7280;
    }

    .btn-secondary:hover {
        background: #4b5563;
        border-color: #4b5563;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .calculation-preview {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 1rem;
    }

    .calculation-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .calculation-item:last-child {
        border-bottom: none;
        font-weight: 700;
        color: #10b981;
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

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>{{ isset($payrollReport) ? 'Edit Payroll Report' : 'Create Payroll Report' }} 💰</h1>
            <p>{{ isset($payrollReport) ? 'Update payroll information' : 'Generate a new payroll report for an employee' }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Payroll
            </a>
        </div>
    </div>
</div>

<form action="{{ isset($payrollReport) ? route('admin.payroll.update', $payrollReport) : route('admin.payroll.store') }}" method="POST" id="payrollForm">
    @csrf
    @if(isset($payrollReport))
        @method('PUT')
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h5>Employee & Period Information</h5>
        </div>
        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="employee_id" class="form-label">Employee *</label>
                    <select name="employee_id" id="employee_id" class="form-select" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (isset($payrollReport) && $payrollReport->employee_id == $employee->id) ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="period_start" class="form-label">Period Start *</label>
                    <input type="date" name="period_start" id="period_start" class="form-control"
                           value="{{ isset($payrollReport) ? $payrollReport->period_start->format('Y-m-d') : old('period_start', now()->startOfMonth()->format('Y-m-d')) }}" required>
                    @error('period_start')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="period_end" class="form-label">Period End *</label>
                    <input type="date" name="period_end" id="period_end" class="form-control"
                           value="{{ isset($payrollReport) ? $payrollReport->period_end->format('Y-m-d') : old('period_end', now()->endOfMonth()->format('Y-m-d')) }}" required>
                    @error('period_end')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h5>Hours & Pay Information</h5>
        </div>
        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="total_hours" class="form-label">Total Hours *</label>
                    <input type="number" name="total_hours" id="total_hours" class="form-control" step="0.01" min="0"
                           value="{{ isset($payrollReport) ? $payrollReport->total_hours : old('total_hours') }}" required>
                    @error('total_hours')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="regular_hours" class="form-label">Regular Hours *</label>
                    <input type="number" name="regular_hours" id="regular_hours" class="form-control" step="0.01" min="0"
                           value="{{ isset($payrollReport) ? $payrollReport->regular_hours : old('regular_hours') }}" required>
                    @error('regular_hours')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="overtime_hours" class="form-label">Overtime Hours</label>
                    <input type="number" name="overtime_hours" id="overtime_hours" class="form-control" step="0.01" min="0"
                           value="{{ isset($payrollReport) ? $payrollReport->overtime_hours : old('overtime_hours') }}">
                    @error('overtime_hours')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deductions" class="form-label">Deductions</label>
                    <input type="number" name="deductions" id="deductions" class="form-control" step="0.01" min="0"
                           value="{{ isset($payrollReport) ? $payrollReport->deductions : old('deductions') }}">
                    @error('deductions')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if(isset($payrollReport))
            <div class="form-row">
                <div class="form-group">
                    <label for="payment_status" class="form-label">Payment Status *</label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="pending" {{ $payrollReport->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $payrollReport->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('payment_status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif

            <div class="form-group">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ isset($payrollReport) ? $payrollReport->notes : old('notes') }}</textarea>
                @error('notes')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Calculation Preview -->
            <div class="calculation-preview" id="calculationPreview" style="display: none;">
                <h6 style="margin-bottom: 1rem; color: #1f2937;">Pay Calculation Preview</h6>
                <div id="calculationItems">
                    <!-- Dynamic calculation items will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            {{ isset($payrollReport) ? 'Update Report' : 'Create Report' }}
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const regularHoursInput = document.getElementById('regular_hours');
    const overtimeHoursInput = document.getElementById('overtime_hours');
    const deductionsInput = document.getElementById('deductions');
    const calculationPreview = document.getElementById('calculationPreview');
    const calculationItems = document.getElementById('calculationItems');

    // Employee data (you might want to fetch this from the server)
    const employees = @json($employees);

    function updateCalculation() {
        const employeeId = employeeSelect.value;
        const regularHours = parseFloat(regularHoursInput.value) || 0;
        const overtimeHours = parseFloat(overtimeHoursInput.value) || 0;
        const deductions = parseFloat(deductionsInput.value) || 0;

        if (!employeeId) {
            calculationPreview.style.display = 'none';
            return;
        }

        const employee = employees.find(emp => emp.id == employeeId);
        if (!employee) return;

        const hourlyRate = employee.hourly_rate || 0;
        const regularPay = regularHours * hourlyRate;
        const overtimePay = overtimeHours * (hourlyRate * 1.5);
        const totalPay = regularPay + overtimePay - deductions;

        calculationItems.innerHTML = `
            <div class="calculation-item">
                <span class="calculation-label">Hourly Rate:</span>
                <span class="calculation-value">$${hourlyRate.toFixed(2)}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Regular Pay (${regularHours}h):</span>
                <span class="calculation-value">$${regularPay.toFixed(2)}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Overtime Pay (${overtimeHours}h @ 1.5x):</span>
                <span class="calculation-value">$${overtimePay.toFixed(2)}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Deductions:</span>
                <span class="calculation-value">$${deductions.toFixed(2)}</span>
            </div>
            <div class="calculation-item">
                <span class="calculation-label">Total Pay:</span>
                <span class="calculation-value">$${totalPay.toFixed(2)}</span>
            </div>
        `;

        calculationPreview.style.display = 'block';
    }

    employeeSelect.addEventListener('change', updateCalculation);
    regularHoursInput.addEventListener('input', updateCalculation);
    overtimeHoursInput.addEventListener('input', updateCalculation);
    deductionsInput.addEventListener('input', updateCalculation);

    // Initial calculation if editing
    @if(isset($payrollReport))
        updateCalculation();
    @endif
});
</script>

@endsection
