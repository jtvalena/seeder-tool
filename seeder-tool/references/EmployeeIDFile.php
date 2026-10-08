<?php

namespace App\Models;

use App\Traits\MorphMap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeIDFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_id_files';

    protected $fillable = [
        'employee_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
        'deleted_by',
    ];

    use MorphMap;

    public function getFilePath()
    {
        return 'employee_id_file';
    }

    public function getRelationId(){
        return 'employee_id';
    }

    public function getPath(){
        return 'hr/employee_id_file';
    }

    public function getDisk(){
        return 'employee_id_file';
    }

    public function getFillables($request){
        $this->employee_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
