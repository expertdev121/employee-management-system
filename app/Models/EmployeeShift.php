<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'shift_date',
        'status',
        'rejection_reason',
        'responded_at',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'responded_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('shift_date', $date);
    }

    public function scopePaginateEmployeeShifts($query, $perPage = 15)
    {
        return $query->with(['employee', 'shift'])->latest()->paginate($perPage);
    }

    public function scopePaginateForEmployee($query, $employeeId, $perPage = 15)
    {
        return $query->where('employee_id', $employeeId)->with('shift')->latest()->paginate($perPage);
    }

    public function attendanceLog()
    {
        return $this->hasOne(\App\Models\AttendanceLog::class, 'employee_id', 'employee_id')
            ->whereColumn('attendance_date', 'shift_date');
    }
}
