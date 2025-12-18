<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'department',
        'hourly_rate',
        'max_shifts_per_week',
        'max_shifts_per_day',
        'social_id',
        'full_address',
        'floor',
        'business_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'hourly_rate' => 'decimal:2',
    ];

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class, 'employee_id');
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id');
    }

    public function approvedAttendances(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'approved_by');
    }

    public function shiftRequests(): HasMany
    {
        return $this->hasMany(ShiftRequest::class, 'employee_id');
    }

    public function approvedShiftRequests(): HasMany
    {
        return $this->hasMany(ShiftRequest::class, 'approved_by');
    }

    public function payrollReports(): HasMany
    {
        return $this->hasMany(PayrollReport::class, 'employee_id');
    }

    public function generatedPayrollReports(): HasMany
    {
        return $this->hasMany(PayrollReport::class, 'generated_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Set default shift limits based on role
            if ($user->role === 'employee') {
                $user->max_shifts_per_day = $user->max_shifts_per_day ?? 4;
            } elseif ($user->role === 'client') {
                $user->max_shifts_per_week = $user->max_shifts_per_week ?? 4;
            }
        });

        static::deleting(function ($user) {
            // Delete related records when user is permanently deleted
            $user->employeeShifts()->delete();
            $user->attendanceLogs()->delete();
            $user->approvedAttendances()->delete();
            $user->shiftRequests()->delete();
            $user->approvedShiftRequests()->delete();
            $user->payrollReports()->delete();
            $user->generatedPayrollReports()->delete();
            $user->auditLogs()->delete();
        });
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeEmployees($query)
    {
        return $query->where('role', 'employee');
    }

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    public function scopePaginateEmployees($query, $perPage = 15)
    {
        return $query->employees()->active()->paginate($perPage);
    }

    public function scopePaginateAdmins($query, $perPage = 15)
    {
        return $query->admins()->paginate($perPage);
    }

    public function scopePaginateClients($query, $perPage = 15)
    {
        return $query->clients()->active()->paginate($perPage);
    }
}
