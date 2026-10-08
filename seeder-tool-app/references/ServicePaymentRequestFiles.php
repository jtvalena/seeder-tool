<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class ServicePaymentRequestFiles extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 
        'path', 
        'extension', 
        'upload_by', 
        'upload_type', 
        'type', 
        'service_payment_request_id', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    protected $appends = [
        'uploaded_by_name'
    ];
    public function getUploadedByNameAttribute()
    {
        $user = User::where('id', $this->upload_by)->first();
        return $user->name;
    }
    public function getFilePath()
    {
        return 'service_payment_request_files';
    }

    public function getRelationId(){
        return 'service_payment_request_id';
    }

    public function getPath(){
        return 'accounting/service_payment_request_files';
    }

    public function getDisk(){
        return 'service_payment_request_files';
    }

    public function getFillables($request){
        $this->service_payment_request_id  = $request['m_id'];
        $this->upload_by = Auth::id() ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        $this->upload_type = $request['upload_type'] ?? 1;
        return $this;
    }
}
