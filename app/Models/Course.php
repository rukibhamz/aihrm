<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class);
    }

    public function userCompletion()
    {
        return $this->hasOne(CourseCompletion::class)->where('user_id', auth()->id());
    }
}
