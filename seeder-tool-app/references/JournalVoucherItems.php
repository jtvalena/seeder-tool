<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class JournalVoucherItems extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'jv_id',
        'coa_id',
        'debit_amount',
        'credit_amount',
        'particulars',
        'recognition_month',
        'department_id',
        'job_id',
        'enter_bill_id',
        // 'remarks',
    ];
    protected $appends = ['coa_by_name'];

    public function coa_name()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'coa_id');
    }
    public function getCOAByNameAttribute(){
        $account_name = !empty($this->coa_name) ? $this->coa_name->account_name : '';
        return $account_name;
    }
}
