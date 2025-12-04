<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentItem extends Model
{
    protected $fillable = ['assessment_id', 'criteria_id', 'score', 'evidence_photos', 'comment'];

    public function criteria()
    {
        return $this->belongsTo(AssessmentCriteria::class, 'criteria_id');
    }
}
