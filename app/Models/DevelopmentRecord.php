<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentRecord extends Model
{
    protected $fillable = [
        'student_id', 'recorded_at', 
        'physical_desc', 'emotional_desc', 'behavior_desc', 'learning_desc'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
