<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesmanDailyReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'salesman_monthly_reports_id',
        'salesman_id',
        'set_by',
        'objective',
        'actual',
        'percent',
        'balance',
        'date',
        'exceed_amount',
        'metric_id'
    ];
}
