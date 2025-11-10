<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shift_name',
        'shift_type',
        'start_time',
        'end_time',
        'max_capacity',
        'description',
        'location',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_capacity' => 'integer',
    ];

    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('shift_type', $type);
    }

    public function scopePaginateShifts($query, $perPage = 15)
    {
        return $query->active()->latest()->paginate($perPage);
    }

    public function scopePaginateAllShifts($query, $perPage = 15)
    {
        return $query->latest()->paginate($perPage);
    }

    public function getDurationHoursAttribute()
    {
        $start = $this->start_time;
        $end = $this->end_time;

        // If end time is before or equal to start time, assume it spans midnight
        if ($end->lessThanOrEqualTo($start)) {
            $end = $end->copy()->addDay();
        }

        return $start->diffInHours($end);
    }
}
