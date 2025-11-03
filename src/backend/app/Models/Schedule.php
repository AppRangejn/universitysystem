<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['group_id', 'day', 'time', 'subject', 'room', 'teacher', 'week', 'pair_number'];

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
