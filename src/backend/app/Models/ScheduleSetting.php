<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ScheduleSetting extends Model
{
    use HasFactory;
    protected $fillable = ['days', 'pair_count'];

    protected $casts = [
        'days' => 'array',
    ];
}
