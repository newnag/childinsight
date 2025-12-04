<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    protected $fillable = [
        'center_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'photo_path',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
