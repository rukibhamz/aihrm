<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Payroll extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $guarded = [];

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTotalCostAttribute()
    {
        return $this->payslips->sum('net_salary'); // Simplified cost
    }
}
