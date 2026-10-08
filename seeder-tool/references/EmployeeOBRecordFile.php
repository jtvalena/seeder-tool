<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeOBRecordFile extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_ob_record_files';

    protected $fillable = [
        'ob_record_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
        'upload_type',
        'deleted_by',
    ];

    protected $appends = ['uploaded_by_name'];

    public function get_uploaded_by_name()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUploadedByNameAttribute(){
        $name = !empty($this->get_uploaded_by_name) ? $this->get_uploaded_by_name->name : '';
        unset($this->get_uploaded_by_name);
        return $name;
    }

    public function getFilePath()
    {
        return 'ob_record_files';
    }

    public function getRelationId(){
        return 'ob_record_id';
    }

    public function getPath(){
        return 'hr/ob_record_files';
    }

    public function getDisk(){
        return 'ob_record_files';
    }

    public function getFillables($request){
        $this->ob_record_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
