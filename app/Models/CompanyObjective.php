<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyObjective extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'progress',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function goals()
    {
        return $this->hasMany(Goal::class, 'company_objective_id');
    }
}
