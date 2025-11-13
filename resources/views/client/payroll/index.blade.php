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

        /* Amount Display */
        .amount-display {
            font-weight: 600;
            color: #1f2937;
        }

        .amount-primary {
            color: var(--primary);
            font-size: 1.1em;
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

    <!-- Monthly Summary -->
    <div class="stats-section">
        <div class="stats-card">
            <h3>{{ number_format($previousMonthHours, 1) }}h</h3>
            <p>Last Month Hours</p>
        </div>
        <div class="stats-card">
            <h3>${{ number_format($previousMonthPay, 2) }}</h3>
            <p>Last Month Pay</p>
        </div>
        <div class="stats-card">
            <h3>{{ number_format($currentMonthHours, 1) }}h</h3>
            <p>This Month Hours</p>
        </div>
        <div class="stats-card">
            <h3>${{ number_format($currentMonthPay, 2) }}</h3>
            <p>This Month Pay</p>
        </div>
    </div>
    <!-- Payroll Date Range Filter -->
    <div class="dashboard-card" style="margin-bottom: 1.5rem;">
        <div class="card-body" style="padding: 1.5rem;">
            <form method="GET" action="{{ route('employee.payroll.index') }}"
                style="display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end;">

                <div style="flex: 1 1 200px; min-width:180px;">
                    <label for="start_date" style="display:block; font-weight:600; color:#374151; margin-bottom:0.5rem;">
                        Start Date
                    </label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:8px;">
                </div>

                <div style="flex: 1 1 200px; min-width:180px;">
                    <label for="end_date" style="display:block; font-weight:600; color:#374151; margin-bottom:0.5rem;">
                        End Date
                    </label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:8px;">
                </div>

                <div style="display:flex; gap:0.75rem; align-items:center;">
                    <button type="submit"
                        style="background:var(--primary); color:white; padding:0.625rem 1rem; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
                        <i class="fas fa-filter"></i> Apply
                    </button>

                    <a href="{{ route('employee.payroll.index') }}"
                        style="background:#e5e7eb; color:#374151; padding:0.625rem 1rem; border-radius:8px; font-weight:600; text-decoration:none;">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>

                <!-- optional small helper text when both dates selected -->
                <div style="width:100%; color:#6b7280; margin-top:0.5rem; font-size:0.9rem;">
                    @if (request('start_date') || request('end_date'))
                        Showing results
                        @if (request('start_date'))
                            from <strong>{{ request('start_date') }}</strong>
                        @endif
                        @if (request('end_date'))
                            to <strong>{{ request('end_date') }}</strong>
                        @endif
                    @else
                        Showing all payroll records
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Records -->
    <div class="dashboard-card">
        <div class="card-body">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h5 style="margin: 0; color: #1f2937; font-weight: 600;">Payroll Records</h5>
                <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.875rem;">Your accepted shifts and calculated
                    earnings</p>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Shift Accepted Date</th>
                            <th>Shift Name</th>
                            <th>Hours</th>
                            <th>Hourly Rate</th>
                            <th>Total Pay</th>
                            <th>Status</th>
                            <th>Accepted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrollRecords as $payroll)
                            <tr>
                                <td>
                                    <div style="font-weight: 500; color: #1f2937;">
                                        {{ $payroll->shift_date->format('M d, Y') }}
                                    </div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">
                                        {{ $payroll->shift_date->format('l') }}
                                    </div>
                                </td>
                                <td>
                                    @if ($payroll->employeeShift && $payroll->employeeShift->shift)
                                        <div style="font-weight: 500; color: #1f2937;">
                                            {{ $payroll->employeeShift->shift->shift_name }}
                                        </div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            {{ $payroll->employeeShift->shift->start_time->format('H:i') }} -
                                            {{ $payroll->employeeShift->shift->end_time->format('H:i') }}
                                        </div>
                                    @else
                                        <span style="color: #9ca3af;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1f2937;">
                                        {{ number_format($payroll->total_hours, 1) }}h
                                    </div>
                                </td>
                                <td>
                                    <span class="amount-display">${{ number_format($payroll->hourly_rate, 2) }}/hr</span>
                                </td>
                                <td>
                                    <span
                                        class="amount-display amount-primary">${{ number_format($payroll->total_pay, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-success">
                                        {{ ucfirst($payroll->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem; color: #6b7280;">
                                        {{ $payroll->accepted_at->format('M d, H:i') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">💰</div>
                                        <div class="empty-state-text">No payroll records found</div>
                                        <div class="empty-state-subtext">Your payroll records will appear here once you
                                            accept shifts</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($payrollRecords->hasPages())
                <div style="display: flex; justify-content: center; padding: 1.5rem; border-top: 1px solid #e5e7eb;">
                    {{ $payrollRecords->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
