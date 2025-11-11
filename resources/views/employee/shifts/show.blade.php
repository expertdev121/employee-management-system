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

    /* Shift Details Card */
    .shift-details-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .shift-details-card .card-header {
        background: #f9fafb;
        padding: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .shift-details-card .card-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .shift-details-card .card-body {
        padding: 1.5rem;
    }

    /* Details Grid */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-value i {
        color: var(--primary);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-accepted {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-completed {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .btn-action-primary {
        background: var(--primary);
        color: white;
    }

    .btn-action-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-action-danger {
        background: var(--danger);
        color: white;
    }

    .btn-action-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-action-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-action-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Rejection Reason */
    .rejection-reason {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .rejection-reason h4 {
        margin: 0 0 0.5rem 0;
        color: #dc2626;
        font-size: 1rem;
        font-weight: 600;
    }

    .rejection-reason p {
        margin: 0;
        color: #7f1d1d;
        font-size: 0.9375rem;
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

        .details-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-calendar-alt"></i>
            <h2>Shift Details</h2>
        </div>
        <a href="{{ route('employee.shifts.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Shifts</span>
        </a>
    </div>
</div>

<div class="shift-details-card">
    <div class="card-header">
        <h3>
            <i class="fas fa-info-circle"></i>
            Shift Information
        </h3>
    </div>
    <div class="card-body">
        <div class="details-grid">
            <div class="detail-item">
                <span class="detail-label">Shift Date</span>
                <span class="detail-value">
                    <i class="fas fa-calendar-day"></i>
                    @if($employeeShift->shift_date)
                        @if($employeeShift->shift_date)
                            {{ $employeeShift->shift_date->format('l, F j, Y') }}
                        @else
                            Recurring Shift
                        @endif
                    @else
                        Recurring Shift (No specific date)
                    @endif
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Shift Time</span>
                <span class="detail-value">
                    <i class="fas fa-clock"></i>
                    {{ $employeeShift->shift->start_time->format('H:i') }} - {{ $employeeShift->shift->end_time->format('H:i') }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Duration</span>
                <span class="detail-value">
                    <i class="fas fa-hourglass-half"></i>
                    {{ $employeeShift->shift->duration_hours }} hours
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Department</span>
                <span class="detail-value">
                    <i class="fas fa-building"></i>
                    {{ $employeeShift->shift->department->name ?? 'N/A' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Location</span>
                <span class="detail-value">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $employeeShift->shift->location ?? 'N/A' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="status-badge status-{{ $employeeShift->status }}">
                        {{ ucfirst($employeeShift->status) }}
                    </span>
                </span>
            </div>

            @if($employeeShift->responded_at)
            <div class="detail-item">
                <span class="detail-label">Response Date</span>
                <span class="detail-value">
                    <i class="fas fa-reply"></i>
                    {{ $employeeShift->responded_at->format('M j, Y H:i') }}
                </span>
            </div>
            @endif
        </div>

        @if($employeeShift->status === 'rejected' && $employeeShift->rejection_reason)
        <div class="rejection-reason">
            <h4><i class="fas fa-exclamation-triangle"></i> Rejection Reason</h4>
            <p>{{ $employeeShift->rejection_reason }}</p>
        </div>
        @endif

        @if($employeeShift->status === 'pending')
        <div class="action-buttons">
            <button class="btn-action btn-action-primary accept-shift" data-shift-id="{{ $employeeShift->id }}">
                <i class="fas fa-check"></i>
                <span>Accept Shift</span>
            </button>
            <button class="btn-action btn-action-danger reject-shift" data-shift-id="{{ $employeeShift->id }}">
                <i class="fas fa-times"></i>
                <span>Reject Shift</span>
            </button>
        </div>
        @endif
    </div>
</div>

@if($employeeShift->status === 'pending')
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
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($employeeShift->status === 'pending')
    let currentShiftId = {{ $employeeShift->id }};
    const modal = document.getElementById('rejectionModal');
    const rejectionForm = document.getElementById('rejectionForm');
    const rejectionReason = document.getElementById('rejectionReason');

    // Accept shift button
    document.querySelector('.accept-shift').addEventListener('click', function() {
        if (!this.disabled) {
            acceptShift(currentShiftId);
        }
    });

    // Reject shift button
    document.querySelector('.reject-shift').addEventListener('click', function() {
        if (!this.disabled) {
            modal.style.display = 'block';
            rejectionReason.focus();
        }
    });

    // Close modal
    document.querySelector('.close').addEventListener('click', function() {
        modal.style.display = 'none';
        rejectionReason.value = '';
    });

    document.getElementById('cancelReject').addEventListener('click', function() {
        modal.style.display = 'none';
        rejectionReason.value = '';
    });

    // Handle rejection form submission
    rejectionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        rejectShift(currentShiftId, rejectionReason.value);
    });

    function acceptShift(shiftId) {
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
                location.reload();
            } else {
                // Re-enable buttons on error
                document.querySelectorAll('.accept-shift:disabled').forEach(button => {
                    enableButton(button);
                });
                alert(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable buttons on error
            document.querySelectorAll('.accept-shift:disabled').forEach(button => {
                enableButton(button);
            });
            showMessage(data.success || 'Shift accepted successfully', 'success');
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
    @endif
});
</script>
@endsection
