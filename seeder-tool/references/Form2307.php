<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Form2307 extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'form_2307s';

    protected $fillable = [
        'transaction_number',
        'remarks', 
        'form_date_from', 
        'form_date_to', 
        'total_amount', 
        'status',
        'vendor_id',
        'company_id',
        'store_branch_id',
        'prepared_by', 
        'last_updated_by', 
        'deleted_by', 
        'approved_by', 
        'approved_at',
        'printed',
        'last_printed_by',
        'last_printed_at',
    ];
    public function form_2307_items()
    {
        return $this->hasMany(Form2307Item::class, 'form_2307_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $lastTransactionNumber = static::withTrashed()->max('transaction_number');
            $model->transaction_number = str_pad(
                $lastTransactionNumber ? 
                ++$lastTransactionNumber : 
                '00000001', 8, '0', STR_PAD_LEFT
            );
        });
    }
}

