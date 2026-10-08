<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'uploaded_by_name'
    ];

    public function getUploadedByNameAttribute(){
        return User::find($this->uploaded_by)->name ?? '';
    }

    public function getFilePath()
    {
        return 'customer_uploads';
    }

    public function getRelationId(){
        return 'customer_id';
    }

    public function getPath(){
        return 'masterdata/customer_uploads';
    }

    public function getDisk(){
        return 'customer_uploads';
    }

    public function getFillables($request){
        $this->customer_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        // $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
