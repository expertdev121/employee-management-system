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
        .request-type {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #1f2937;
        }

        .request-type i {
            color: var(--primary);
            font-size: 0.875rem;
        }

        .request-description {
            color: #6b7280;
            font-size: 0.9375rem;
        }

        .request-date {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .request-date-main {
            font-weight: 500;
            color: #1f2937;
        }

        .request-date-day {
            font-size: 0.875rem;
            color: #6b7280;
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

        /* Action Buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
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
                <i class="fas fa-paper-plane"></i>
                <h2>My Requests</h2>
            </div>
            <a href="{{ route('employee.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="filter-section"
        style="margin-bottom: 2rem; background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
        <form method="GET" action="{{ route('employee.requests.index') }}" class="filter-form"
            style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div class="filter-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <label for="status" style="font-weight: 600; color: #374151;">Filter by Status:</label>
                <select name="status" id="status" onchange="this.form.submit()"
                    style="padding: 0.5rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; background: white; color: #374151; font-size: 0.9375rem;">
                    <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>All Requests</option>
                    <option value="assigned" {{ $currentStatus === 'assigned' ? 'selected' : '' }}>assigned</option>
                    <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ $currentStatus === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ $currentStatus === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        </form>
    </div>

    <div class="dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Request Type</th>
                            <th>Description</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Response</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>
                                    <div class="request-type">
                                        <i
                                            class="fas fa-{{ $request->request_type === 'shift' ? 'clock' : 'question-circle' }}"></i>
                                        <span>{{ ucfirst($request->request_type ?? 'Shift') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="request-description">
                                        {{ $request->description ?? ($request->shift_date ? 'Shift request for ' . $request->shift_date->format('M d, Y') : 'Recurring shift request') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="request-date">
                                        <span class="request-date-main">{{ $request->created_at->format('M d, Y') }}</span>
                                        <span class="request-date-day">{{ $request->created_at->format('l') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge-custom badge-{{ $request->status === 'accepted' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->rejection_reason ?? 'N/A' }}</td>
                                <td>
                                    @if ($request->status === 'pending')
                                        <button class="btn-action btn-action-danger">
                                            <i class="fas fa-times"></i>
                                            <span>Cancel</span>
                                        </button>
                                    @else
                                        <button class="btn-action btn-action-primary">
                                            <i class="fas fa-eye"></i>
                                            <span>Details</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📋</div>
                                        <div class="empty-state-text">No requests found</div>
                                        <div class="empty-state-subtext">Your shift requests and other submissions will
                                            appear here</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($requests->hasPages())
                <div class="pagination-wrapper">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
