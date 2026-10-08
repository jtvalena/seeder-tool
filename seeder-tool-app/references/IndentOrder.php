<?php

namespace App\Models;

use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class IndentOrder extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'doc_num',
        'doc_date',
        'customer_id',
        'status',
        'remarks',
        'customer_po_num',
        'cancellation_type',
        'cancellation_details',
        'cancellation_requested_by',
        'cancellation_requested_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'approved_by',
        'canceled_by',
        'approved_at',
        'canceled_at',
    ];

    protected $appends = [];

    protected $casts = [
        'cancellation_details' => 'array',
    ];

    public function details()
    {
        return $this->hasMany(IndentOrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function files()
    {
        return $this->hasMany(IndentOrderFile::class);
    }

    public function sales_quotations()
    {
        return $this->hasMany(\App\Models\Sales\SalesQuotation::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function approved_by_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canceled_by_user()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    public function cancellation_requested_by_user()
    {
        return $this->belongsTo(User::class, 'cancellation_requested_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = IndentOrder::select('doc_num')->whereRaw('length(doc_num)=6')->max('doc_num');
            if(!empty($latest)){
                $next_num = ($latest != null) ? intval($latest) + 1: 1;
                $model->doc_num = str_pad((string)$next_num, 6, '0', STR_PAD_LEFT);
            }else{
                $model->doc_num = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
