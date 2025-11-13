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

    /* Profile Layout */
    .profile-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
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
        padding: 1.5rem;
    }

    /* Profile Information */
    .profile-info-section h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .info-item {
        margin-bottom: 1rem;
    }

    .info-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-value {
        font-size: 0.9375rem;
        color: #1f2937;
        margin: 0;
    }

    /* Edit Button */
    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--success);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* Profile Avatar Section */
    .profile-avatar-section {
        text-align: center;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        background: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .profile-email {
        color: #6b7280;
        margin-bottom: 1rem;
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

    .badge-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    /* Quick Stats */
    .quick-stats h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        text-align: center;
    }

    .stat-item h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--success);
        margin-bottom: 0.25rem;
    }

    .stat-item small {
        color: #6b7280;
        font-weight: 500;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: #1f2937;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.9375rem;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-secondary {
        background: #6b7280;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-success {
        background: var(--success);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
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

        .profile-layout {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i class="fas fa-user"></i>
            <h2>My Profile</h2>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>
</div>

<div class="profile-layout">
    <div class="dashboard-card">
        <div class="card-body">
            <h5>Profile Information</h5>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <p class="info-value">{{ auth()->user()->name }}</p>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <p class="info-value">{{ auth()->user()->email }}</p>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <p class="info-value">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                </div>
                <div class="info-item">
                    <span class="info-label">Department</span>
                    <p class="info-value">{{ auth()->user()->department ?? 'Not assigned' }}</p>
                </div>
                <div class="info-item">
                    <span class="info-label">Hourly Rate</span>
                    <p class="info-value">${{ number_format((float) auth()->user()->hourly_rate, 2) }}</p>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <p class="info-value">
                        <span class="badge-custom badge-{{ auth()->user()->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst(auth()->user()->status) }}
                        </span>
                    </p>
                </div>
            </div>
            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-edit"></i> Edit Profile
            </button>
        </div>
    </div>

    <div>
        <div class="dashboard-card">
            <div class="card-body profile-avatar-section">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="profile-name">{{ auth()->user()->name }}</h4>
                <p class="profile-email">{{ auth()->user()->email }}</p>
                <span class="badge-custom badge-success">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>

        <div class="dashboard-card" style="margin-top: 1.5rem;">
            <div class="card-body">
                <h5>Quick Stats</h5>
                <div class="stats-grid">
                    <div class="stat-item">
                        <h3>{{ $totalShifts ?? 0 }}</h3>
                        <small>Total Shifts</small>
                    </div>
                    <div class="stat-item">
                        <h3>{{ number_format($totalHours ?? 0, 1) }}</h3>
                        <small>Total Hours</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" value="{{ auth()->user()->phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->department }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
