<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSetting extends Model
{
    protected $fillable = ['days', 'pair_count'];

    protected $casts = [
        'days' => 'array',
    ];
}
