<?php

namespace App\Models;

use App\Models\Accounting\CreditMemo;
use App\Models\CreditMemoFile;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreditMemoChina;
use App\Models\Service\ScRepairFormFile;
use OwenIt\Auditing\Contracts\Auditable;

class CreditMemoChinaItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'files_count',
    ];

    public function credit_memo_china()
    {
        return $this->belongsTo(CreditMemoChina::class, 'credit_memo_china_id')->withTrashed();
    }

    public function credit_memo_files()
    {
        return $this->hasManyThrough(CreditMemoFile::class, CreditMemo::class, 'id', 'credit_memo_id', 'credit_memo_id');
    }

    public function sc_repair_form_files()
    {
        return $this->hasManyThrough(ScRepairFormFile::class, CreditMemo::class, 'id', 'sc_repair_form_id', 'credit_memo_id', 'sc_repair_form_id');
    }

    public function getFilesCountAttribute()
    {
        return $this->credit_memo_files->count() + $this->sc_repair_form_files->count();
    }
}
