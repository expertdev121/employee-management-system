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
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
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

    .shift-card {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .shift-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .shift-card-item {
        display: flex;
        margin-bottom: 0.625rem;
        font-size: 0.9375rem;
    }

    .shift-card-item:last-child {
        margin-bottom: 0;
    }

    .shift-card-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 120px;
    }

    .shift-card-value {
        color: #1f2937;
        font-weight: 500;
    }

    .attendance-card {
        background: #f9fafb;
        border-left: 4px solid #3b82f6;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .attendance-card:hover {
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .attendance-card-item {
        display: flex;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .attendance-card-item:last-child {
        margin-bottom: 0;
    }

    .attendance-card-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 100px;
    }

    .attendance-card-value {
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
            <i class="fas fa-user"></i>
            <h2>Client Details</h2>
        </div>
        <div class="action-buttons-group">
            <a href="{{ route('admin.clients.edit', $client) }}" class="btn-custom btn-warning-custom">
                <i class="fas fa-edit"></i>
                <span>Edit Client</span>
            </a>
            <a href="{{ route('admin.clients.index') }}" class="btn-custom btn-secondary-custom">
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
                        Personal Information
                    </h4>
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $client->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 0.375rem;"></i>
                            {{ $client->email }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">
                            @if($client->phone)
                                <i class="fas fa-phone" style="color: #10b981; margin-right: 0.375rem;"></i>
                                {{ $client->phone }}
                            @else
                                <span style="color: #9ca3af;">N/A</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department:</span>
                        <span class="info-value">
                            @if($client->department)
                                <i class="fas fa-building" style="color: #f59e0b; margin-right: 0.375rem;"></i>
                                {{ $client->department }}
                            @else
                                <span style="color: #9ca3af;">N/A</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Hourly Rate:</span>
                        <span class="info-value">
                            <i class="fas fa-dollar-sign" style="color: #10b981; margin-right: 0.375rem;"></i>
                            ${{ number_format($client->hourly_rate, 2) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="badge-custom badge-{{ $client->status === 'active' ? 'success' : 'secondary' }}">
                                @if($client->status === 'active')
                                    <i class="fas fa-check" style="font-size: 0.75rem; margin-right: 0.375rem;"></i>
                                @endif
                                {{ ucfirst($client->status) }}
                            </span>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Joined:</span>
                        <span class="info-value">
                            <i class="fas fa-calendar" style="color: #6b7280; margin-right: 0.375rem;"></i>
                            {{ $client->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-clock"></i>
                        Assigned Shifts
                    </h4>

                    <!-- Assign Shift Form -->
                    <div class="mb-4">
                        <button type="button" class="btn-custom btn-primary-custom" data-bs-toggle="modal" data-bs-target="#assignShiftModal">
                            <i class="fas fa-plus"></i>
                            <span>Assign New Shift</span>
                        </button>
                    </div>

                    @if($client->clientShifts->count() > 0)
                        @foreach($client->clientShifts as $shift)
                            <div class="shift-card">
                                <div class="shift-card-item">
                                    <span class="shift-card-label">Shift:</span>
                                    <span class="shift-card-value">{{ $shift->shift->shift_name }}</span>
                                </div>
                                <div class="shift-card-item">
                                    <span class="shift-card-label">Type:</span>
                                    <span class="shift-card-value">
                                        <span class="badge-custom badge-primary">{{ ucfirst($shift->shift->shift_type) }}</span>
                                    </span>
                                </div>
                                <div class="shift-card-item">
                                    <span class="shift-card-label">Time:</span>
                                    <span class="shift-card-value">{{ $shift->shift->start_time->format('H:i') }} - {{ $shift->shift->end_time->format('H:i') }}</span>
                                </div>

                                @if($shift->shift_date)
                                    <div class="shift-card-item">
                                        <span class="shift-card-label">Assigned Date:</span>
                                        @if($shift->shift_date)
                                            <span class="shift-card-value">{{ $shift->shift_date->format('M d, Y') }}</span>
                                        @else
                                            <span class="shift-card-value">Recurring</span>
                                        @endif
                                    </div>
                                @endif
                                @if(in_array($shift->status, ['assigned', 'accepted']))
                                    <div class="shift-card-item">
                                        <span class="shift-card-label">Actions:</span>
                                        <span class="shift-card-value">
                                            <form action="{{ route('admin.clients.unassign-shift', $shift) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to unassign this shift?')">
                                                    <i class="fas fa-times"></i> Unassign
                                                </button>
                                            </form>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-clock"></i>
                            <p>No shifts assigned</p>
                        </div>
                    @endif
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Assign Shift Modal -->
<div class="modal fade" id="assignShiftModal" tabindex="-1" role="dialog" aria-labelledby="assignShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignShiftModalLabel">
                    <i class="fas fa-plus-circle"></i>
                    Assign Shift to Client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.clients.assign-shift', $client) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="shift_id">Select Shift</label>
                        <select name="shift_id" id="shift_id" class="form-control" required>
                            <option value="">Choose a shift...</option>
                            @foreach(\App\Models\Shift::all() as $shift)
                                <option value="{{ $shift->id }}">
                                    {{ $shift->shift_name }} ({{ $shift->start_time->format('H:i') }} - {{ $shift->end_time->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Assign Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
