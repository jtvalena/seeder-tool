<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderMassCancellationRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_order_mass_cancellation_requests';

    protected $fillable = [
        'request_number',
        'requested_by',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'total_so_count',
        'total_amount',
        'service',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'service' => 'boolean',
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderMassCancellationRequestItem::class, 'so_mass_cancellation_request_id');
    }

    public function salesOrders()
    {
        return $this->hasManyThrough(
            SalesOrder::class,
            SalesOrderMassCancellationRequestItem::class,
            'so_mass_cancellation_request_id',
            'id',
            'id',
            'sales_order_id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->request_number)) {
                $model->request_number = self::generateRequestNumber();
            }
        });
    }

    public static function generateRequestNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "SOMCR-{$year}{$month}-";

        // Use lockForUpdate to prevent race conditions and optimize with max() instead of orderBy
        $lastRequest = self::where('request_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRequest) {
            $lastNumber = intval(substr($lastRequest->request_number, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}


