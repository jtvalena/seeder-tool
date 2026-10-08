<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;
    protected $appends = ['uploaded_by_name'];
    protected $fillable = [
        'sales_invoice_id',
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by',
    ];

    public function getFilePath()
    {
        return 'sales_invoice_uploads';
    }

    public function getRelationId(){
        return 'sales_invoice_id';
    }

    public function getPath(){
        return 'accounting/sales_invoice_uploads';
    }

    public function getDisk(){
        return 'sales_invoice_uploads';
    }

    public function getFillables($request){
        $this->sales_invoice_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }

    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }
}
