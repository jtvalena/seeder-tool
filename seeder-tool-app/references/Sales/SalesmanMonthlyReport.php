<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesmanMonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'salesman_id',
        'metric_id',
        'set_by',
        'objective',
        'actual',
        'percent',
        'balance',
        'month',
        'year',
        'daily_objective'
    ];
}
