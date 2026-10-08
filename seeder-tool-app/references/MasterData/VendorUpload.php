<?php

namespace App\Models\MasterData;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class VendorUpload extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'vendor_id', 
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
        return 'vendor';
    }

    public function getRelationId(){
        return 'vendor_id';
    }

    public function getPath(){
        return 'masterdata/vendor';
    }

    public function getDisk(){
        return 'vendor';
    }

    public function getFillables($request){
        $this->vendor_id = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
