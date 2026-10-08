<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminJobOrderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'extension',
        'type',
        'upload_by',
        'admin_job_order_id',
    ];
    protected $appends = ['uploaded_by_name'];
    public function uploaded_by_user()
    {
        return $this->belongsTo(User::class, 'upload_by');
    }
    public function getUploadedByNameAttribute(){
        $name = !empty($this->uploaded_by_user) ? $this->uploaded_by_user->name : '';
        unset($this->uploaded_by_user);
        return $name;
    }
    public function getFilePath()
    {
        return 'admin_job_order_files';
    }
    public function getRelationId()
    {
        return 'admin_job_order_id';
    }
    public function getPath()
    {
        return 'admin/admin_job_order_files';
    }
    public function getDisk()
    {
        return 'admin_job_order_files';
    }

    public function getFillables($request){
        $this->admin_job_order_id = $request['m_id'];
        $this->upload_by = Auth::id() ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
