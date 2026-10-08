<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AdminPurchaseOrderAmendmentFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin_purchase_order_amendment_files';

    protected $fillable = [
        'admin_purchase_order_amendment_id',
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by',
    ];

    protected $appends = ['uploaded_by_name'];

    public function getFilePath()
    {
        return 'po_amendment_file_uploads';
    }

    public function getRelationId()
    {
        return 'admin_purchase_order_amendment_id';
    }

    public function getPath()
    {
        return 'admin/po_amendment_file_uploads';
    }

    public function getDisk()
    {
        return 'po_amendment_file_uploads';
    }

    public function getUploadedByNameAttribute()
    {
        return User::where('id', $this->uploaded_by)->value('name');
    }

    public function getFillables($request)
    {
        $this->admin_purchase_order_amendment_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';

        return $this;
    }
}
