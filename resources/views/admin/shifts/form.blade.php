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
            <i class="fas fa-calendar-plus" style="color: #10b981;"></i>
            {{ isset($shift) ? 'Edit Shift' : 'Create New Shift' }}
        </h1>
        <a href="{{ route('admin.shifts.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Shifts
        </a>
    </div>

    <div class="form-card">
        <form action="{{ isset($shift) ? route('admin.shifts.update', $shift) : route('admin.shifts.store') }}"
              method="POST">
            @csrf
            @if(isset($shift))
                @method('PUT')
            @endif

            <div class="form-row">
                <!-- Shift Name -->
                <div class="form-group">
                    <label for="shift_name" class="form-label">
                        Shift Name <span class="required">*</span>
                    </label>
                    <input type="text"
                           id="shift_name"
                           name="shift_name"
                           value="{{ old('shift_name', $shift->shift_name ?? '') }}"
                           class="form-input @error('shift_name') is-invalid @enderror"
                           placeholder="Enter shift name"
                           required>
                    @error('shift_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Shift Type -->
                <div class="form-group">
                    <label for="shift_type" class="form-label">
                        Shift Type <span class="required">*</span>
                    </label>
                    <select id="shift_type"
                            name="shift_type"
                            class="form-select @error('shift_type') is-invalid @enderror"
                            required>
                        <option value="" disabled selected>Select Shift Type</option>
                        <option value="morning" {{ old('shift_type', $shift->shift_type ?? '') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="evening" {{ old('shift_type', $shift->shift_type ?? '') == 'evening' ? 'selected' : '' }}>Evening</option>
                        <option value="night" {{ old('shift_type', $shift->shift_type ?? '') == 'night' ? 'selected' : '' }}>Night</option>
                        <option value="custom" {{ old('shift_type', $shift->shift_type ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @error('shift_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <!-- Start Time -->
                <div class="form-group">
                    <label for="start_time" class="form-label">
                        Start Time <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="time"
                               id="start_time"
                               name="start_time"
                               value="{{ old('start_time', isset($shift) ? $shift->start_time->format('H:i') : '') }}"
                               class="form-input @error('start_time') is-invalid @enderror"
                               required>
                        <span class="input-icon">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 6v4l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </div>
                    @error('start_time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- End Time -->
                <div class="form-group">
                    <label for="end_time" class="form-label">
                        End Time <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="time"
                               id="end_time"
                               name="end_time"
                               value="{{ old('end_time', isset($shift) ? $shift->end_time->format('H:i') : '') }}"
                               class="form-input @error('end_time') is-invalid @enderror"
                               required>
                        <span class="input-icon">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 6v4l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </div>
                    @error('end_time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <!-- Maximum Capacity -->
                <div class="form-group">
                    <label for="max_capacity" class="form-label">
                        Maximum Capacity <span class="required">*</span>
                    </label>
                    <input type="number"
                           id="max_capacity"
                           name="max_capacity"
                           value="{{ old('max_capacity', $shift->max_capacity ?? '1') }}"
                           class="form-input @error('max_capacity') is-invalid @enderror"
                           min="1"
                           placeholder="1"
                           required>
                    @error('max_capacity')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Duration Hours -->
                <div class="form-group">
                    <label for="duration_hours" class="form-label">
                        Duration (Hours)
                    </label>
                    <input type="text"
                           id="duration_hours"
                           name="duration_hours"
                           value="{{ old('duration_hours', isset($shift) ? $shift->duration_hours : '') }}"
                           class="form-input"
                           placeholder="Auto-calculated"
                           readonly>
                </div>
            </div>

            <div class="form-row">
                <!-- Location -->
                <div class="form-group full-width">
                    <label for="location" class="form-label">Location</label>
                    <input type="text"
                           id="location"
                           name="location"
                           value="{{ old('location', $shift->location ?? '') }}"
                           class="form-input @error('location') is-invalid @enderror"
                           placeholder="Enter location">
                    @error('location')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
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
                        <option value="active" {{ old('status', $shift->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $shift->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description" class="form-label">Description</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          placeholder="Add a description for this shift..."
                          class="form-textarea @error('description') is-invalid @enderror">{{ old('description', $shift->description ?? '') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i>
                    {{ isset($shift) ? 'Update Shift' : 'Create Shift' }}
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

    // Calculate duration hours when start or end time changes
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const durationInput = document.getElementById('duration_hours');

    function calculateDuration() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (startTime && endTime) {
            const start = new Date(`1970-01-01T${startTime}:00`);
            const end = new Date(`1970-01-01T${endTime}:00`);

            let diff = end - start;

            // If end time is before start time, assume it spans midnight
            if (diff <= 0) {
                diff = (end - start) + (24 * 60 * 60 * 1000);
            }

            const hours = diff / (1000 * 60 * 60);
            durationInput.value = hours.toFixed(1);
        } else {
            durationInput.value = '';
        }
    }

    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);

    // Calculate duration on page load if values exist
    calculateDuration();
});
</script>
@endsection
