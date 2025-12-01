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
                <i class="fas fa-clock" style="color: #10b981;"></i>
                Edit Client Attendance Record
            </h1>
            <a href="{{ route('admin.client-attendance.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Client Attendance
            </a>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.client-attendance.update', $attendanceLog) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <!-- Client Select -->
                    <div class="form-group">
                        <label for="employee_id" class="form-label">
                            Client <span class="required">*</span>
                        </label>
                        <select id="employee_id" name="employee_id"
                            class="form-select @error('employee_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Select Client</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id', $attendanceLog->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} ({{ $employee->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Shift Select -->
                    <div class="form-group">
                        <label for="shift_id" class="form-label">
                            Shift <span class="required">*</span>
                        </label>
                        <select id="shift_id" name="shift_id" class="form-select @error('shift_id') is-invalid @enderror"
                            required>
                            <option value="" disabled selected>Select Shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}"
                                    {{ old('shift_id', $attendanceLog->shift_id ?? '') == $shift->id ? 'selected' : '' }}>

                                    {{ $shift->shift_name }}
                                    ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                        @error('shift_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <!-- Attendance Date -->
                    <div class="form-group">
                        <label for="attendance_date" class="form-label">
                            Attendance Date <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="date" id="attendance_date" name="attendance_date"
                                value="{{ old('attendance_date', $attendanceLog->attendance_date ?? date('Y-m-d')) }}"
                                class="form-input @error('attendance_date') is-invalid @enderror" required>
                            <span class="input-icon">
                                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="4" width="14" height="14" rx="2" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <path d="M3 8h14M7 2v4M13 2v4" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                        </div>
                        @error('attendance_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Select -->
                    <div class="form-group">
                        <label for="status" class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror"
                            required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="present"
                                {{ old('status', $attendanceLog->status ?? '') == 'present' ? 'selected' : '' }}>Present
                            </option>
                            <option value="absent"
                                {{ old('status', $attendanceLog->status ?? '') == 'absent' ? 'selected' : '' }}>Absent
                            </option>
                            <option value="late"
                                {{ old('status', $attendanceLog->status ?? '') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="early_leave"
                                {{ old('status', $attendanceLog->status ?? '') == 'early_leave' ? 'selected' : '' }}>Early
                                Leave</option>
                            <option value="on_break"
                                {{ old('status', $attendanceLog->status ?? '') == 'on_break' ? 'selected' : '' }}>On Break
                            </option>
                        </select>
                        @error('status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Notes Textarea -->
                <div class="form-group full-width">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="notes" name="notes" rows="4" placeholder="Add any additional notes here..."
                        class="form-textarea @error('notes') is-invalid @enderror">{{ old('notes', $attendanceLog->notes ?? '') }}</textarea>
                    @error('notes')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.client-attendance.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Update Client Attendance
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
