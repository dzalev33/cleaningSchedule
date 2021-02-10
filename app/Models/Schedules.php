<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;
    protected $casts = [
        'working_days' => 'array',

    ];
    protected $fillable = ['working_days'];
}
