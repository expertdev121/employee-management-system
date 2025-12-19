@extends('layouts.app')


@section('content')
<style>
/* Form Container Styles */
.form-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background: #f9fafb;
    min-height: 100vh;
}


.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}


.form-title {
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 8px;
}


.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #1f2937;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}


.btn-back:hover {
    background: #111827;
}


.form-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}


/* Form Layout */
.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    margin-bottom: 24px;
}


@media (min-width: 768px) {
    .form-row {
        grid-template-columns: repeat(2, 1fr);
    }
}


/* Form Group */
.form-group {
    display: flex;
    flex-direction: column;
}


.form-group.full-width {
    grid-column: 1 / -1;
}


/* Form Label */
.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}


.required {
    color: #ef4444;
    margin-left: 2px;
}


/* Input Wrapper for Icons */
.input-wrapper {
    position: relative;
}


/* Base Input Styles */
.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 12px 16px;
    font-size: 15px;
    color: #1f2937;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.2s;
    outline: none;
    font-family: inherit;
}


.form-input {
    height: 48px;
}


/* Enhanced Select Dropdown Styling */
.form-select {
    height: 48px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    padding-right: 44px;
    cursor: pointer;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 7.5L10 12.5L15 7.5' stroke='%239ca3af' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 20px;
}


/* When a select option is chosen, make text darker */
.form-select:not([value=""]):valid,
.form-select.has-value {
    color: #1f2937;
}


/* Placeholder style for select */
.form-select option[value=""][disabled] {
    color: #9ca3af;
}


.form-select option:not([value=""]) {
    color: #1f2937;
}


/* Focus states for select */
.form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 7.5L10 12.5L15 7.5' stroke='%233b82f6' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
}


.form-select:hover {
    border-color: #d1d5db;
}


/* Time and Date Input Styles */
.form-input[type="time"],
.form-input[type="date"] {
    padding-right: 44px;
    cursor: pointer;
}


/* Textarea */
.form-textarea {
    min-height: 120px;
    resize: vertical;
    padding: 12px 16px;
    line-height: 1.6;
}


/* Placeholder styles */
.form-input::placeholder,
.form-textarea::placeholder {
    color: #9ca3af;
}


/* Hover states */
.form-input:hover,
.form-textarea:hover {
    border-color: #d1d5db;
}


/* Focus states */
.form-input:focus,
.form-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}


/* Error States */
.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid {
    border-color: #ef4444;
}


.form-input.is-invalid:focus,
.form-select.is-invalid:focus,
.form-textarea.is-invalid:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}


.error-message {
    margin-top: 6px;
    font-size: 13px;
    color: #ef4444;
}


/* Icons - Only for time/date inputs now */
.input-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #9ca3af;
}


.input-icon svg {
    width: 20px;
    height: 20px;
}


/* Hide native date/time picker icons */
.form-input[type="date"]::-webkit-calendar-picker-indicator,
.form-input[type="time"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 0;
    width: 40px;
    height: 100%;
    cursor: pointer;
}


/* Firefox specific select arrow hiding */
.form-select::-ms-expand {
    display: none;
}


/* Currency Input Styling */
.input-with-currency {
    padding-left: 32px;
}


.currency-symbol {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-weight: 500;
    pointer-events: none;
    z-index: 1;
}


/* Buttons */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 32px;
    padding-top: 32px;
    border-top: 1px solid #e5e7eb;
}


.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}


.btn-secondary {
    background: #ffffff;
    color: #374151;
    border: 1px solid #d1d5db;
}


.btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}


.btn-success {
    background: #10b981;
    color: #ffffff;
}


.btn-success:hover {
    background: #059669;
}
</style>


<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">
            <i class="fas fa-user-plus" style="color: #10b981;"></i>
            {{ isset($client) ? 'Edit Client' : 'Add New Client' }}
        </h1>
        <a href="{{ route('admin.clients.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Clients
        </a>
    </div>


    <div class="form-card">
        <form action="{{ isset($client) ? route('admin.clients.update', $client) : route('admin.clients.store') }}"
              method="POST">
            @csrf
            @if(isset($client))
                @method('PUT')
            @endif


            <div class="form-row">
                <!-- Full Name -->
                <div class="form-group">
                    <label for="name" class="form-label">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $client->name ?? '') }}"
                           class="form-input @error('name') is-invalid @enderror"
                           placeholder="Enter full name"
                           required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address <span class="required">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $client->email ?? '') }}"
                           class="form-input @error('email') is-invalid @enderror"
                           placeholder="Enter email address"
                           required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>





            <div class="form-row">
                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $client->phone ?? '') }}"
                           class="form-input @error('phone') is-invalid @enderror"
                           placeholder="Enter phone number">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Department -->
                <div class="form-group">
                    <label for="department" class="form-label">Department</label>
                    <input type="text"
                           id="department"
                           name="department"
                           value="{{ old('department', $client->department ?? '') }}"
                           class="form-input @error('department') is-invalid @enderror"
                           placeholder="Enter department">
                    @error('department')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="form-row">
                <!-- Business Name -->
                <div class="form-group">
                    <label for="business_name" class="form-label">Business Name</label>
                    <input type="text"
                           id="business_name"
                           name="business_name"
                           value="{{ old('business_name', $client->business_name ?? '') }}"
                           class="form-input @error('business_name') is-invalid @enderror"
                           placeholder="Enter business name">
                    @error('business_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Floor -->
                <div class="form-group">
                    <label for="floor" class="form-label">Floor</label>
                    <input type="text"
                           id="floor"
                           name="floor"
                           value="{{ old('floor', $client->floor ?? '') }}"
                           class="form-input @error('floor') is-invalid @enderror"
                           placeholder="Enter floor">
                    @error('floor')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="form-row">
                <!-- Full Address -->
                <div class="form-group full-width">
                    <label for="full_address" class="form-label">Full Address</label>
                    <textarea id="full_address"
                              name="full_address"
                              class="form-textarea @error('full_address') is-invalid @enderror"
                              placeholder="Enter full address">{{ old('full_address', $client->full_address ?? '') }}</textarea>
                    @error('full_address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="form-row">
                <!-- Hourly Rate -->
                <div class="form-group">
                    <label for="hourly_rate" class="form-label">
                        Hourly Rate <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="currency-symbol">$</span>
                        <input type="number"
                               id="hourly_rate"
                               name="hourly_rate"
                               value="{{ old('hourly_rate', $client->hourly_rate ?? '15.00') }}"
                               class="form-input input-with-currency @error('hourly_rate') is-invalid @enderror"
                               step="0.01"
                               min="0"
                               placeholder="15.00"
                               required>
                    </div>
                    @error('hourly_rate')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>


                @if(isset($client))
                <!-- Status -->
                <div class="form-group">
                    <label for="status" class="form-label">
                        Status <span class="required">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="active" {{ old('status', $client->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $client->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        {{-- <option value="blocked" {{ old('status', $client->status ?? 'active') == 'blocked' ? 'selected' : '' }}>Blocked</option> --}}
                    </select>
                    @error('status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                @endif
            </div>


            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    {{ isset($client) ? 'Update Client' : 'Create Client' }}
                </button>
            </div>
        </form>
    </div>
</div>


<script>
// Add 'has-value' class to selects when they have a value
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.form-select');


    selects.forEach(select => {
        // Check on load
        if (select.value && select.value !== '') {
            select.classList.add('has-value');
        }


        // Check on change
        select.addEventListener('change', function() {
            if (this.value && this.value !== '') {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });
});
</script>
@endsection
