<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'faculty_id'];

    public function faculty() {
        return $this->belongsTo(Faculty::class);
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }
}
