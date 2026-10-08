<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeSalaryMovementFile extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_salary_movement_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
        'upload_type',
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
        return 'employee_salary_movement_files';
    }

    public function getRelationId(){
        return 'employee_salary_movement_id';
    }

    public function getPath(){
        return 'hr/employee_salary_movement_files';
    }

    public function getDisk(){
        return 'employee_salary_movement_files';
    }

    public function getFillables($request){
        $this->employee_salary_movement_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->upload_type = $request['upload_type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }

    // Upload Type
    // 1 - Eval
    // 2 - PAN
}
