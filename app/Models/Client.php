<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'department',
        'hourly_rate',
        'max_shifts_per_week',
        'social_id',
        'full_address',
        'floor',
        'business_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the shifts assigned to this client.
     */
    public function employeeShifts(): MorphMany
    {
        return $this->morphMany(EmployeeShift::class, 'employee');
    }

    /**
     * Get the attendance logs for this client.
     */
    public function attendanceLogs(): MorphMany
    {
        return $this->morphMany(AttendanceLog::class, 'employee');
    }

    /**
     * Scope for active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
