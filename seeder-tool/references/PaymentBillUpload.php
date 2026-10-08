<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentBillUpload extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payment_voucher_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
    ];
    protected $appends = [
        'uploaded_by_name'
    ];
    public function getUploadedByNameAttribute()
    {
        $user = User::where('id', $this->uploaded_by)->first();
        return $user->name;
    }
    public function getFilePath()
    {
        return 'pb_file_uploads';
    }

    public function getRelationId(){
        return 'pv_id';
    }

    public function getPath(){
        return 'accounting/paybills';
    }

    public function getDisk(){
        return 'pb_file_uploads';
    }

    public function getFillables($request){
        $this->pv_id = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
