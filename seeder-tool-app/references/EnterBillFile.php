<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class EnterBillFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enter_bill_id',
        'name',
        'path',
        'extension',
        'upload_type',
        'type',
        'uploaded_by',
        'created_at',
        'deleted_by',
    ];

    public function getFilePath()
    {
        return 'enter_bill_files';
    }

    public function getRelationId(){
        return 'enter_bill_id';
    }

    public function getPath(){
        return 'admin/enter_bill_files';
    }

    public function getDisk(){
        return 'enter_bill_files';
    }

    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->enter_bill_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        // 0 = General, 2 = Liquidation Attachments; default to General (0) for legacy/unset files
        $this->upload_type = !empty($request['upload_type']) ? (int)$request['upload_type'] : 0;
        return $this;
    }
}