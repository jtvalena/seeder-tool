<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CreditMemoChinaItem;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class CreditMemoChina extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($creditMemoChina) {
            if (is_null($creditMemoChina->doc_num)) {
                DB::transaction(function () use ($creditMemoChina) {
                    $lastDocNum = DB::table('credit_memo_chinas')
                        ->lockForUpdate()
                        ->max('doc_num') ?? 0;
                    $creditMemoChina->doc_num = $lastDocNum + 1;
                });
            }
        });
    }

    public function credit_memo_china_items()
    {
        return $this->hasMany(CreditMemoChinaItem::class, 'credit_memo_china_id');
    }
    public function credit_memo_china_files()
    {
        return $this->hasMany(CreditMemoChinaFile::class, 'credit_memo_china_id');
    }
}
