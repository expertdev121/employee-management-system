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
            color: var(--success);
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

        .action-buttons-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-custom {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -1px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-warning-custom {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
        }

        .btn-warning-custom:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -1px rgba(245, 158, 11, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary-custom:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #1f2937;
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .dashboard-card .card-body {
            padding: 2rem;
        }

        .info-section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: var(--primary);
            font-size: 1.125rem;
        }

        .info-item {
            display: flex;
            padding: 0.875rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 140px;
            font-size: 0.9375rem;
        }

        .info-value {
            color: #1f2937;
            font-weight: 500;
            flex: 1;
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

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .stats-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stats-card-item {
            display: flex;
            margin-bottom: 0.625rem;
            font-size: 0.9375rem;
        }

        .stats-card-item:last-child {
            margin-bottom: 0;
        }

        .stats-card-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 120px;
        }

        .stats-card-value {
            color: #1f2937;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .page-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons-group {
                flex-direction: column;
            }

            .btn-custom {
                justify-content: center;
            }

            .info-item {
                flex-direction: column;
                gap: 0.25rem;
            }

            .info-label {
                min-width: auto;
            }
        }
    </style>

    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <i class="fas fa-calendar-check"></i>
                <h2>Employee Attendance Details</h2>
            </div>
            <div class="action-buttons-group">
                <a href="{{ route('admin.employee-attendance.edit', $attendanceLog) }}" class="btn-custom btn-warning-custom">
                    <i class="fas fa-edit"></i>
                    <span>Edit Record</span>
                </a>
                <a href="{{ route('admin.employee-attendance.index') }}" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to List</span>
                </a>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-section">
                        <h4 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Attendance Information
                        </h4>
                        <div class="info-item">
                            <span class="info-label">Employee:</span>
                            <span class="info-value">
                                @if ($attendanceLog->employee)
                                    {{ $attendanceLog->employee->name }}
                                @else
                                    Unknown Employee
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">
                                <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 0.375rem;"></i>
                                @if ($attendanceLog->employee)
                                    {{ $attendanceLog->employee->email }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Attendance Date:</span>
                            <span class="info-value">
                                <i class="fas fa-calendar" style="color: #10b981; margin-right: 0.375rem;"></i>
                                {{ $attendanceLog->attendance_date->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span
                                    class="badge-custom badge-{{ $attendanceLog->status === 'present' ? 'success' : ($attendanceLog->status === 'absent' ? 'danger' : 'warning') }}">
                                    @if ($attendanceLog->status === 'present')
                                        <i class="fas fa-check" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                    @endif
                                    {{ ucfirst($attendanceLog->status) }}
                                </span>
                            </span>
                        </div>
                        @if ($attendanceLog->login_time)
                            <div class="info-item">
                                <span class="info-label">Login Time:</span>
                                <span class="info-value">
                                    <i class="fas fa-clock" style="color: #10b981; margin-right: 0.375rem;"></i>
                                    {{ $attendanceLog->login_time->format('H:i') }}
                                </span>
                            </div>
                        @endif
                        @if ($attendanceLog->logout_time)
                            <div class="info-item">
                                <span class="info-label">Logout Time:</span>
                                <span class="info-value">
                                    <i class="fas fa-clock" style="color: #ef4444; margin-right: 0.375rem;"></i>
                                    {{ $attendanceLog->logout_time->format('H:i') }}
                                </span>
                            </div>
                        @endif
                        @if ($attendanceLog->total_hours)
                            <div class="info-item">
                                <span class="info-label">Total Hours:</span>
                                <span class="info-value">
                                    <i class="fas fa-hourglass-half" style="color: #f59e0b; margin-right: 0.375rem;"></i>
                                    {{ number_format($attendanceLog->total_hours, 2) }}
                                </span>
                            </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Notes:</span>
                            <span class="info-value">{{ $attendanceLog->notes ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created:</span>
                            <span class="info-value">
                                <i class="fas fa-calendar-plus" style="color: #6b7280; margin-right: 0.375rem;"></i>
                                {{ $attendanceLog->created_at->format('M d, Y H:i') }}
                            </span>
                        </div>
                        @if ($attendanceLog->updated_at != $attendanceLog->created_at)
                            <div class="info-item">
                                <span class="info-label">Last Updated:</span>
                                <span class="info-value">
                                    <i class="fas fa-edit" style="color: #6b7280; margin-right: 0.375rem;"></i>
                                    {{ $attendanceLog->updated_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-section">
                        <h4 class="section-title">
                            <i class="fas fa-clock"></i>
                            Shift Information
                        </h4>
                        @if ($attendanceLog->shift)
                            <div class="info-item">
                                <span class="info-label">Shift Name:</span>
                                <span class="info-value">{{ $attendanceLog->shift->shift_name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Shift Type:</span>
                                <span class="info-value">
                                    <span
                                        class="badge-custom badge-primary">{{ ucfirst($attendanceLog->shift->shift_type) }}</span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Scheduled Time:</span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($attendanceLog->shift->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($attendanceLog->shift->end_time)->format('H:i') }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Location:</span>
                                <span class="info-value">
                                    @if ($attendanceLog->shift->location)
                                        <i class="fas fa-map-marker-alt"
                                            style="color: #f59e0b; margin-right: 0.375rem;"></i>
                                        {{ $attendanceLog->shift->location }}
                                    @else
                                        <span style="color: #9ca3af;">N/A</span>
                                    @endif
                                </span>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-clock"></i>
                                <p>No shift assigned for this attendance record</p>
                            </div>
                        @endif
                    </div>

                    <div class="info-section">
                        <h4 class="section-title">
                            <i class="fas fa-chart-bar"></i>
                            Employee Statistics
                        </h4>
                        @php
                            $employeeStats = [
                                'total_days' => $attendanceLog->employee
                                    ? $attendanceLog->employee->attendanceLogs()->count()
                                    : 0,
                                'present_days' => $attendanceLog->employee
                                    ? $attendanceLog->employee->attendanceLogs()->where('status', 'present')->count()
                                    : 0,
                                'absent_days' => $attendanceLog->employee
                                    ? $attendanceLog->employee->attendanceLogs()->where('status', 'absent')->count()
                                    : 0,
                            ];
                            $attendance_rate =
                                $employeeStats['total_days'] > 0
                                    ? round(($employeeStats['present_days'] / $employeeStats['total_days']) * 100, 1)
                                    : 0;
                        @endphp
                        <div class="stats-card">
                            <div class="stats-card-item">
                                <span class="stats-card-label">Total Records:</span>
                                <span class="stats-card-value">{{ $employeeStats['total_days'] }}</span>
                            </div>
                            <div class="stats-card-item">
                                <span class="stats-card-label">Present Days:</span>
                                <span class="stats-card-value">{{ $employeeStats['present_days'] }}</span>
                            </div>
                            <div class="stats-card-item">
                                <span class="stats-card-label">Absent Days:</span>
                                <span class="stats-card-value">{{ $employeeStats['absent_days'] }}</span>
                            </div>
                            <div class="stats-card-item">
                                <span class="stats-card-label">Attendance Rate:</span>
                                <span class="stats-card-value">
                                    <span
                                        class="badge-custom badge-{{ $attendance_rate >= 80 ? 'success' : ($attendance_rate >= 60 ? 'warning' : 'danger') }}">
                                        {{ $attendance_rate }}%
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
