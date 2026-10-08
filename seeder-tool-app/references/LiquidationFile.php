<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class LiquidationFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'liquidation_id',
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by'
    ];
    public function getFilePath()
    {
        return 'liquidation_files';
    }
    public function getRelationId(){
        return 'liquidation_id';
    }
    public function getPath(){
        return 'accounting/liquidation_files';
    }
    public function getDisk(){
        return 'liquidation_files';
    }
    
    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }
    public function getFillables($request){
        $this->liquidation_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
