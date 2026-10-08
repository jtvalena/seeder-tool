<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeMovementFile extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_movement_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
        'deleted_by',
    ];

    protected $appends = ['uploaded_by_name'];

    public function get_uploaded_by_name()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUploadedByNameAttribute()
    {
        $name = !empty($this->get_uploaded_by_name) ? $this->get_uploaded_by_name->name : '';
        unset($this->get_uploaded_by_name);
        return $name;
    }

    public function getFilePath()
    {
        return 'employee_movement_files';
    }

    public function getRelationId()
    {
        return 'employee_movement_id';
    }

    public function getPath()
    {
        return 'hr/employee_movement_files';
    }

    public function getDisk()
    {
        return 'employee_movement_files';
    }

    public function getFillables($request)
    {
        $this->employee_movement_id = $request['m_id'];
        $this->uploaded_by          = Auth::user()->id ?? 0;
        $this->name                 = $request['file_name'] ?? '';
        $this->path                 = $request['filePath'] ?? '';
        $this->type                 = $request['type'] ?? '';
        $this->extension            = $request['extension'] ?? '';
        return $this;
    }
}
