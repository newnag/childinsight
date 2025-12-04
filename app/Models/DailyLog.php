<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    protected $fillable = [
        'student_id', 'date', 
        'milk_requisition', 'milk_amount',
        'food_consumed', 'food_quantity', 'nutrient_quality',
        'activity_photos'
    ];

    protected $casts = [
        'milk_requisition' => 'boolean',
        'activity_photos' => 'array',
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
