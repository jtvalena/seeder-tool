<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeDataGovermentId extends Model
{
    use HasFactory;

    public function getFilePath()
    {
        return 'employee_data_government_ids';
    }

    public function getRelationId(){
        return 'employee_id';
    }

    public function getPath(){
        return 'hr/employee_data_government_ids';
    }

    public function getDisk(){
        return 'employee_data_government_ids';
    }
    
    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->employee_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->id_type = $request['government_id_type'] ?? '';
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
