<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_type',
        'shift_id',
        'attendance_date',
        'login_time',
        'logout_time',
        'total_hours_minutes',
        'total_hours',
        'break_duration_minutes',
        'status',
        'is_overtime',
        'overtime_hours',
        'notes',
        'ip_address',
        'is_manual_entry',
        'approved_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
        'total_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'is_overtime' => 'boolean',
        'is_manual_entry' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('attendance_date', $date);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function breakLogs()
    {
        return $this->hasMany(BreakLog::class);
    }

    public function activeBreak()
    {
        return $this->hasOne(BreakLog::class)->whereNull('break_end');
    }

    public function scopePaginateAttendance($query, $perPage = 15)
    {
        return $query->with(['employee', 'shift'])->latest()->paginate($perPage);
    }

    public function scopePaginateForEmployee($query, $employeeId, $perPage = 15)
    {
        return $query->forEmployee($employeeId)->latest()->paginate($perPage);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attendanceLog) {
            // Delete related break logs when attendance log is deleted
            $attendanceLog->breakLogs()->delete();
        });
    }
}
