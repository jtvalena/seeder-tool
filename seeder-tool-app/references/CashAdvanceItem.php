<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CashAdvanceItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payroll_period_detail_id',
        'payroll_period_id',
        'cash_advance_id',
        'payable_in_date',
        'skipped',
        'amount',
        'paid',
    ];

    protected $appends = ['cash_advance_doc_num'];

    public function get_cash_advance_doc_num()
    {
        return $this->belongsTo(CashAdvance::class, 'cash_advance_id');
    }

    public function getCashAdvanceDocNumAttribute(){
        $doc_num = !empty($this->get_cash_advance_doc_num) ? $this->get_cash_advance_doc_num->doc_num : '';
        unset($this->get_cash_advance_doc_num);
        return $doc_num;
    }

}
