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
    .shift-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9375rem;
    }

    .shift-date {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .shift-date-main {
        font-weight: 500;
        color: #1f2937;
    }

    .shift-date-day {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .shift-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .shift-time i {
        color: var(--primary);
        font-size: 0.875rem;
    }

    .shift-location {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
    }

    .shift-location i {
        color: #9ca3af;
        font-size: 0.875rem;
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

    .btn-action-success {
        background: white;
        border-color: #bbf7d0;
        color: #16a34a;
    }

    .btn-action-success:hover {
        background: #f0fdf4;
        border-color: #16a34a;
        color: #15803d;
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

    /* Filter Section (Optional) */
    .filter-section {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-btn:hover,
    .filter-btn.active {
        border-color: var(--primary);
        background: #eff6ff;
        color: var(--primary);
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-clock"></i>
            <h2>My Shifts</h2>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>
</div>

<!-- Optional Filter Section -->
<!-- <div class="filter-section">
    <button class="filter-btn active">All Shifts</button>
    <button class="filter-btn">Upcoming</button>
    <button class="filter-btn">Past</button>
    <button class="filter-btn">Pending</button>
</div> -->

<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Shift Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                    <tr>
                        <td>
                            <div class="shift-name">{{ $shift->shift->shift_name }}</div>
                        </td>
                        <td>
                            <div class="shift-date">
                                @if($shift->shift_date)
                                    @if($shift->shift_date)
                                        <span class="shift-date-main">{{ $shift->shift_date->format('M d, Y') }}</span>
                                    @else
                                        <span class="shift-date-main">Recurring</span>
                                    @endif
                                    @if($shift->shift_date)
                                        <span class="shift-date-day">{{ $shift->shift_date->format('l') }}</span>
                                    @else
                                        <span class="shift-date-day">Ongoing</span>
                                    @endif
                                @else
                                    <span class="shift-date-main">Recurring</span>
                                    <span class="shift-date-day">Ongoing</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="shift-time">
                                <i class="fas fa-clock"></i>
                                <span>{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="shift-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $shift->shift->location ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-custom badge-{{ $shift->status === 'accepted' ? 'success' : (in_array($shift->status, ['pending', 'assigned']) ? 'warning' : 'danger') }}">
                                {{ ucfirst($shift->status) }}
                            </span>
                        </td>
                        <td>
                            @if(in_array($shift->status, ['pending', 'assigned']))
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="btn-action btn-action-primary accept-shift" data-shift-id="{{ $shift->id }}">
                                    <i class="fas fa-check"></i>
                                    <span>Accept</span>
                                </button>
                                <button class="btn-action btn-action-danger reject-shift" data-shift-id="{{ $shift->id }}">
                                    <i class="fas fa-times"></i>
                                    <span>Reject</span>
                                </button>
                            </div>
                            @else
                            <a href="{{ route('employee.shifts.show', $shift) }}" class="btn-action btn-action-primary">
                                <i class="fas fa-eye"></i>
                                <span>Details</span>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">📅</div>
                                <div class="empty-state-text">No shifts assigned</div>
                                <div class="empty-state-subtext">Check back later for new shift assignments</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shifts->hasPages())
        <div class="pagination-wrapper">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 500px; border-radius: 8px;">
        <span class="close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h3 style="margin-top: 0; color: #1f2937;">Reject Shift</h3>
        <form id="rejectionForm">
            <div style="margin-bottom: 1rem;">
                <label for="rejectionReason" style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600;">Reason for rejection:</label>
                <textarea id="rejectionReason" name="rejection_reason" rows="4" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; resize: vertical;" placeholder="Please provide a reason for rejecting this shift..." required></textarea>
            </div>
            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                <button type="button" class="btn-action btn-action-secondary" id="cancelReject" style="background: #6b7280; border-color: #6b7280; color: white;">Cancel</button>
                <button type="submit" class="btn-action btn-action-danger">Reject Shift</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentShiftId = null;
    const modal = document.getElementById('rejectionModal');
    const rejectionForm = document.getElementById('rejectionForm');
    const rejectionReason = document.getElementById('rejectionReason');

    // Accept shift buttons
    document.querySelectorAll('.accept-shift').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                const shiftId = this.getAttribute('data-shift-id');
                acceptShift(shiftId);
            }
        });
    });

    // Reject shift buttons
    document.querySelectorAll('.reject-shift').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                currentShiftId = this.getAttribute('data-shift-id');
                modal.style.display = 'block';
                rejectionReason.focus();
            }
        });
    });

    // Start shift buttons
    document.querySelectorAll('.start-shift-btn').forEach(button => {
        button.addEventListener('click', function() {
            const shiftId = this.getAttribute('data-shift-id');
            startShift(shiftId, this);
        });
    });

    // Close modal
    document.querySelector('.close').addEventListener('click', function() {
        modal.style.display = 'none';
        rejectionReason.value = '';
        currentShiftId = null;
    });

    document.getElementById('cancelReject').addEventListener('click', function() {
        modal.style.display = 'none';
        rejectionReason.value = '';
        currentShiftId = null;
    });

    // Handle rejection form submission
    rejectionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (currentShiftId) {
            rejectShift(currentShiftId, rejectionReason.value);
        }
    });

    function acceptShift(shiftId) {
        // Disable all accept buttons to prevent multiple clicks
        document.querySelectorAll('.accept-shift').forEach(button => {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Accepting...';
        });

        fetch(`/employee/shifts/${shiftId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Shift accepted successfully', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                // Re-enable buttons on error
                document.querySelectorAll('.accept-shift').forEach(button => {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-check"></i> Accept';
                });
                showMessage(data.error || 'An error occurred', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable buttons on error
            document.querySelectorAll('.accept-shift').forEach(button => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check"></i> Accept';
            });
            showMessage('An error occurred while accepting the shift', 'error');
        });
    }

    function rejectShift(shiftId, reason) {
        fetch(`/employee/shifts/${shiftId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rejection_reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.style.display = 'none';
                rejectionReason.value = '';
                currentShiftId = null;
                location.reload();
            } else {
                // Re-enable buttons on error
                document.querySelectorAll('.reject-shift:disabled').forEach(button => {
                    enableButton(button);
                });
                alert(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable buttons on error
            document.querySelectorAll('.reject-shift:disabled').forEach(button => {
                enableButton(button);
            });
            showMessage(data.success || 'Shift rejected successfully', 'success');
        });
    }

    function startShift(shiftId, button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';

        fetch('/employee/attendance/clock-in', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.innerHTML = '<i class="fas fa-check"></i> Started!';
                button.classList.remove('btn-action-success');
                button.classList.add('btn-action-primary');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-play"></i> Start Shift';
                showPopup(data.error || 'Failed to start shift');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-play"></i> Start Shift';
            showPopup('An error occurred while starting the shift');
        });
    }

    function showPopup(message) {
        // Create popup element
        const popup = document.createElement('div');
        popup.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            font-weight: 600;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        `;
        popup.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-exclamation-triangle"></i>
                <span>${message}</span>
            </div>
        `;

        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(popup);

        // Auto remove after 5 seconds
        setTimeout(() => {
            popup.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => {
                if (popup.parentNode) {
                    popup.parentNode.removeChild(popup);
                }
            }, 300);
        }, 5000);
    }
});
</script>
@endsection
