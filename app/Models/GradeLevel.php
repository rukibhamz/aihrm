<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'basic_salary_range'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
