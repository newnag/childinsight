<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['center_id', 'first_name', 'last_name', 'gender', 'dob', 'parent_name', 'parent_contact'];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
