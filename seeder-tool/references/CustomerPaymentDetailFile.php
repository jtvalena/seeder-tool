<?php

namespace App\Models;

use App\Traits\MorphMap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPaymentDetailFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;
    use MorphMap;

    public function getFilePath()
    {
        return 'customer_payment_uploads';
    }

    public function getRelationId(){
        return 'customer_payment_detail_id';
    }

    public function getPath(){
        return 'accounting/customer_payment_uploads';
    }

    public function getDisk(){
        return 'customer_payment_uploads';
    }

    public function getFillables($request){
        $this->customer_payment_detail_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
