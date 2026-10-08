<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditMemoChinaFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'uploaded_by_name',
    ];

    public function getUploadedByNameAttribute()
    {
        $user = User::find($this->uploaded_by);

        return !!$user ? $user->name : '';
    }

    public function getFilePath()
    {
        return 'credit_memo_china_uploads';
    }

    public function getRelationId(){
        return 'credit_memo_china_id';
    }

    public function getPath(){
        return 'accounting/credit_memo_china_uploads';
    }

    public function getDisk(){
        return 'credit_memo_china_uploads';
    }

    public function getFillables($request){
        $this->credit_memo_china_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
