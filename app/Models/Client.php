<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'birth_date',
        'registration_date',
        'active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'date',
        'active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plans()
    {
        return $this->hasMany(ClientPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
