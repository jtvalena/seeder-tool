<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Accounting\StoreStockTransfer;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreStockTransferFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $appends = ['uploaded_by_name', 'deleted_by_name'];

    protected $fillable = [
        'store_stock_transfer_id',
        'credit_memo_id',
        'debit_memo_id',
        'uploaded_by',
        'deleted_by',
        'name',
        'path',
        'extension',
    ];

    public function store_stock_transfer()
    {
        return $this->belongsTo(StoreStockTransfer::class, 'store_stock_transfer_id');
    }

    public function getFilePath()
    {
        return 'store_stock_transfer_uploads';
    }

    public function getRelationId()
    {
        return 'store_stock_transfer_id';
    }

    public function getPath()
    {
        return 'accounting/store_stock_transfer_uploads';
    }

    public function getDisk()
    {
        return 'store_stock_transfer_uploads';
    }

    public function getFillables($request)
    {
        $this->store_stock_transfer_id = $request['m_id'];
        $this->uploaded_by             = Auth::id() ?? 0;
        $this->name                    = $request['file_name'] ?? '';
        $this->path                    = $request['filePath'] ?? '';
        $this->extension               = $request['extension'] ?? '';
        return $this;
    }

    public function getUploadedByNameAttribute()
    {
        return User::where('id', $this->uploaded_by)->value('name');
    }

    public function getDeletedByNameAttribute()
    {
        if (!$this->deleted_by) {
            return null;
        }
        return User::where('id', $this->deleted_by)->value('name');
    }
}
