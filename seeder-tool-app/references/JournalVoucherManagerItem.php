<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class JournalVoucherManagerItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $appends = ['coa_by_name'];

    public function coa_name()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'coa_id','id')->select('account_name');
    }
    public function getCOAByNameAttribute(){
        $account_name = !empty($this->coa_name) ? $this->coa_name->account_name : '';
        return $account_name;
    }
}
