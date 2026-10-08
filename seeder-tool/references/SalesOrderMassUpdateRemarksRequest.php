<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderMassUpdateRemarksRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_order_mass_update_remarks_requests';

    protected $fillable = [
        'request_number',
        'requested_by',
        'new_remarks',
        'update_mode',
        'add_position',
        'update_related_so',
        'update_related_pl',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'total_so_count',
        'total_amount',
    ];

    protected $casts = [
        'approved_at'       => 'datetime',
        'total_amount'      => 'decimal:2',
        'update_related_so' => 'boolean',
        'update_related_pl' => 'boolean',
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
        return $this->hasMany(SalesOrderMassUpdateRemarksRequestItem::class, 'so_mass_update_remarks_request_id');
    }

    public function salesOrders()
    {
        return $this->hasManyThrough(
            Sales\SalesOrder::class,
            SalesOrderMassUpdateRemarksRequestItem::class,
            'so_mass_update_remarks_request_id',
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
        $prefix = "SOMURR-{$year}{$month}-";

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
