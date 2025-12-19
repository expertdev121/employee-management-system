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

        .stat-card.blue::before {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .stat-card.orange::before {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, #8b5cf6, #7c3aed);
        }

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
    <!-- Export Button -->
    <div style="margin-bottom: 1rem; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.payroll.export') }}?{{ http_build_query(request()->query()) }}" class="btn btn-success"
            style="background: #10b981; border-color: #10b981; color: white;">
            <i class="fas fa-file-export"></i> Export Payroll Data
        </a>
    </div>

    <div class="page-header">
        <div>
            <h1>Payroll Management</h1>
            <p>View employee and client payroll based on accepted shifts and hourly rates.</p>
        </div>
    </div>

    <!-- Employee Filter -->
    <form method="GET" action="{{ route('admin.payroll.index') }}" class="employee-filter"
        style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">

        <div style="position: relative; width: 320px;">
            <input type="text" id="employeeSearch" name="employee_name" placeholder="🔍 Search employee name or email..."
                value="{{ isset($employeeId) && $employeeId ? ($employees->firstWhere('id', $employeeId)->name ?? $clients->firstWhere('id', $employeeId)->name ?? '') : '' }}"
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
                        <span style="font-size: 0.8rem; color: #6b7280;">{{ $employee->email }} (Employee)</span>
                    </div>
                @endforeach
                @foreach ($clients as $client)
                    <div class="employee-item" data-id="{{ $client->id }}" data-name="{{ strtolower($client->name) }}"
                        data-email="{{ strtolower($client->email) }}"
                        style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #f3f4f6;"
                        onclick="selectEmployee({{ $client->id }}, '{{ addslashes($client->name) }}')">
                        {{ $client->name }} <br>
                        <span style="font-size: 0.8rem; color: #6b7280;">{{ $client->email }} (Client)</span>
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
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary"
                style="background: #6b7280; color: white; border-color: #6b7280; padding: 0.65rem 1rem; border-radius: 0.5rem; font-size: 0.9rem;">
                Clear Filter
            </a>
        @endif

        <!-- User Type Filter -->
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <label style="font-size: 0.9rem; color: #374151; font-weight: 500;">Filter by:</label>
            <select name="user_type" onchange="this.form.submit()" style="padding: 0.5rem 0.75rem; border-radius: 0.375rem; border: 1px solid #d1d5db; font-size: 0.9rem;">
                <option value="">All Users</option>
                <option value="employee" {{ request('user_type') === 'employee' ? 'selected' : '' }}>Employees Only</option>
                <option value="client" {{ request('user_type') === 'client' ? 'selected' : '' }}>Clients Only</option>
            </select>
        </div>
    </form>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-card-value">${{ number_format($totalProjectedPay + $totalClientProjectedPay, 2) }}</div>
            <div class="stat-card-label">Total Pay</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-card-value">{{ $employeesWithPay->count() + $clientsWithPay->count() }}</div>
            <div class="stat-card-label">Users with Shifts</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-card-value">{{ $employeesWithPay->sum('shifts_count') + $clientsWithPay->sum('shifts_count') }}</div>
            <div class="stat-card-label">Total Accepted Shifts</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-card-value">{{ number_format($employeesWithPay->sum('calculated_hours') + $clientsWithPay->sum('calculated_hours'), 1) }}h</div>
            <div class="stat-card-label">Total Hours</div>
        </div>
    </div>

    @if (!$userType || $userType === 'employee')
    <!-- Employee Payroll -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h5>Employee Payroll</h5>
            <div style="font-size: 0.875rem; color: #6b7280;">Based on accepted shifts and hourly rates</div>
        </div>
        <div class="dashboard-card-body" style="padding: 0;">
            @if ($employeesWithPay->count() > 0)
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Shifts</th>
                                <th>Total Hours</th>
                                <th>Hourly Rate</th>
                                <th>Total Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeesWithPay as $employee)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div
                                                style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-weight: 600;">
                                                {{ substr($employee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 600;">{{ $employee->name }}</div>
                                                <div style="font-size: 0.875rem; color: #6b7280;">{{ $employee->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $employee->shifts_count }}</div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">accepted shifts</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $employee->calculated_hours }}h</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">${{ number_format($employee->hourly_rate, 2) }}/hr
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #10b981;">
                                            ${{ number_format($employee->calculated_pay, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb;">
                    {{ $employeesWithPay->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📅</div>
                    <div class="empty-state-text">No accepted shifts found</div>
                    <div class="empty-state-subtext">Employee payroll will appear here once shifts are accepted</div>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- @if (!$userType || $userType === 'client')
    <!-- Client Payroll -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h5>Client Payroll</h5>
            <div style="font-size: 0.875rem; color: #6b7280;">Based on accepted shifts and hourly rates</div>
        </div>
        <div class="dashboard-card-body" style="padding: 0;">
            @if ($clientsWithPay->count() > 0)
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Shifts</th>
                                <th>Total Hours</th>
                                <th>Hourly Rate</th>
                                <th>Total Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientsWithPay as $client)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div
                                                style="width: 40px; height: 40px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-weight: 600;">
                                                {{ substr($client->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 600;">{{ $client->name }}</div>
                                                <div style="font-size: 0.875rem; color: #6b7280;">{{ $client->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $client->shifts_count }}</div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">accepted shifts</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $client->calculated_hours }}h</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">${{ number_format($client->hourly_rate, 2) }}/hr
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #10b981;">
                                            ${{ number_format($client->calculated_pay, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb;">
                    {{ $clientsWithPay->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">👥</div>
                    <div class="empty-state-text">No accepted shifts found</div>
                    <div class="empty-state-subtext">Client payroll will appear here once shifts are accepted</div>
                </div>
            @endif
        </div>
    </div>
    @endif --}}

    <!-- Payroll Reports Table -->
    @if ($payrollReports->count() > 0)
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5>Generated Payroll Reports</h5>
            </div>
            <div class="dashboard-card-body" style="padding: 0;">
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
                            @foreach ($payrollReports as $report)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div
                                                style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-weight: 600;">
                                                {{ substr($report->employee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 600;">{{ $report->employee->name }}</div>
                                                <div style="font-size: 0.875rem; color: #6b7280;">
                                                    {{ $report->employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 500;">{{ $report->period_start->format('M d') }} -
                                            {{ $report->period_end->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $report->total_hours }}h</div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $report->regular_hours }}h
                                            regular</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #10b981;">
                                            ${{ number_format($report->total_pay, 2) }}</div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">@
                                            ${{ number_format($report->hourly_rate, 2) }}/hr</div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge-custom badge-{{ $report->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($report->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.payroll.show', $report) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                            <a href="{{ route('admin.payroll.edit', $report) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deletePayroll({{ $report->id }}, '{{ $report->employee->name }}')">
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
            </div>
        </div>
    @endif

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
