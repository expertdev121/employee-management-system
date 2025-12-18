<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'shift_id',
        'shift_date',
    ];

    protected $casts = [
        'shift_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('shift_date', $date);
    }

    public function scopeWithoutDate($query)
    {
        return $query->whereNull('shift_date');
    }

    public function scopeWithDate($query)
    {
        return $query->whereNotNull('shift_date');
    }

    public function scopePaginateClientShifts($query, $perPage = 15)
    {
        return $query->with(['client', 'shift'])->latest()->paginate($perPage);
    }

    public function scopePaginateForClient($query, $clientId, $perPage = 15)
    {
        return $query->where('client_id', $clientId)->with('shift')->latest()->paginate($perPage);
    }
}
