<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentCriteria extends Model
{
    protected $fillable = ['category', 'topic', 'max_score'];
}
