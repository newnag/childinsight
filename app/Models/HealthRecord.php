<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    protected $fillable = [
        'student_id', 'recorded_at', 'weight', 'height', 'bmi', 
        'nutrition_status', 'illness', 'health_constraints'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
