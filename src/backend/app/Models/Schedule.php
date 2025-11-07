<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['group_id', 'day', 'time', 'subject', 'room', 'teacher', 'week', 'pair_number'];

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
