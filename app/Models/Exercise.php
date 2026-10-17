<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'muscle_group',
        'equipment',
        'instructions',
    ];

    public function routineExercises()
    {
        return $this->hasMany(RoutineExercise::class);
    }
}
