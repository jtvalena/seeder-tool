<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class RecurringBillUpload extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'recurring_bill_id',
        'uploaded_by',
        'name',
        'file_name',
        'type',
        'extension',
        'created_at'
    ];
    protected $appends = ['uploaded_by_name'];
    public function uploaded_by_user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    public function getUploadedByNameAttribute(){
        $name = !empty($this->uploaded_by_user) ? $this->uploaded_by_user->name : '';
        unset($this->uploaded_by_user);
        return $name;
    }
    public function getFilePath()
    {
        return 'recurring_bill_uploads';
    }
    public function getRelationId()
    {
        return 'recurring_bill_id';
    }
    public function getPath()
    {
        return 'admin/recurring_bill_uploads';
    }
    public function getDisk()
    {
        return 'recurring_bill_uploads';
    }
    public function getFillables($request){
        $this->recurring_bill_id = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        $this->status = 0;
        return $this;
    }
}
