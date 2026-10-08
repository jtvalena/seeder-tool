<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeDeductionFile extends Model
{
    use HasFactory;

    public function getFilePath()
    {
        return 'employee_deduction_files';
    }

    public function getRelationId(){
        return 'employee_deduction_id';
    }

    public function getPath(){
        return 'hr/employee_deduction_files';
    }

    public function getDisk(){
        return 'employee_deduction_files';
    }
    
    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->employee_deduction_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
