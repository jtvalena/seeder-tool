<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeTravelBudgetFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by',
        'deleted_by'
    ];
    protected $appends = [
        'uploaded_by_name'
    ];
    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }
    public function getFilePath()
    {
        return 'employee_travel_budget_files';
    }
    public function getRelationId()
    {
        return 'ebt_id';
    }
    public function getPath()
    {
        return 'accounting/employee_travel_budget_files';
    }
    public function getDisk()
    {
        return 'employee_travel_budget_files';
    }
    public function getFillables($request)
    {
        $this->ebt_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
