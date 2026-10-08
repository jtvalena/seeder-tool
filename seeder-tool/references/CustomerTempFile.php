<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerTempFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function getFilePath()
    {
        return 'customer_temp_uploads';
    }

    public function getRelationId(){
        return 'customer_temp_id';
    }

    public function getPath(){
        return 'masterdata/customer_temp_uploads';
    }

    public function getDisk(){
        return 'customer_temp_uploads';
    }

    public function getFillables($request){
        $this->customer_temp_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        // $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
