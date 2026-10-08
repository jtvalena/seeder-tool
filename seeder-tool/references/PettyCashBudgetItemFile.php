<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class PettyCashBudgetItemFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $appends = ['uploaded_by_name'];
    public function getFilePath()
    {
        return 'petty_cash_budget_item_files';
    }
    public function getRelationId(){
        return 'petty_cash_budget_item_id';
    }
    public function getPath(){
        return 'accounting/petty_cash_budget_item_files';
    }
    public function getDisk(){
        return 'petty_cash_budget_item_files';
    }
    public function uploaded_by_user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    public function getUploadedByNameAttribute(){
        $name = !empty($this->uploaded_by_user) ? $this->uploaded_by_user->name : '';
        unset($this->uploaded_by_user);
        return $name;
    }
    public function getFillables($request){
        $this->petty_cash_budget_item_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
