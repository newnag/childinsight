<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenterAssessment extends Model
{
    protected $fillable = ['center_id', 'assessor_id', 'assessment_date', 'total_score', 'status'];

    public function items()
    {
        return $this->hasMany(AssessmentItem::class, 'assessment_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
