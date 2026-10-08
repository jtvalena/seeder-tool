<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\ChartOfAccounts;

class InternationalPaymentCoa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'description',
        'coa_id',
        'group',
    ];

    public function chart_of_account() {
        return $this->belongsTo(ChartOfAccounts::class, 'coa_id');
    }
}
