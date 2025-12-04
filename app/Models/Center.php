<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    protected $fillable = ['name', 'code', 'district', 'province', 'address', 'status'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
