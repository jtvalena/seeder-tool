<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AdminFasPurchaseRequisitionFiles extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = ['uploaded_by_name'];
    protected $fillable = [
        'admin_fas_purchase_requisition_ids',
        'name',
        'path',
        'type',
        'extension',
        'uploaded_by'
    ];
    public function getFilePath()
    {
        return 'admin_fas_purchase_requisition_files';
    }

    public function getRelationId(){
        return 'admin_fas_purchase_requisition_ids';
    }

    public function getPath(){
        return 'admin/admin_fas_purchase_requisition_files';
    }

    public function getDisk(){
        return 'admin_fas_purchase_requisition_files';
    }

    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->admin_fas_purchase_requisition_ids = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
