<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScReturnReceiptFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'path',
        'uploaded_by',
        'deleted_by',
        'sc_return_receipt_id'
    ];

    public function sc_return_receipt() {
        return $this->belongsTo(ScReturnReceipt::class);
    }
}
