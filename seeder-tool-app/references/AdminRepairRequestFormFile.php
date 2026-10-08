<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AdminRepairRequestFormFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin_repair_request_form_files';

    protected $fillable = [
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by',
        'arrf_id',
    ];

    protected $appends = ['uploaded_by_name'];

    public function uploaded_by_user(){
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    public function getUploadedByNameAttribute(){
        $name = !empty($this->uploaded_by_user) ? $this->uploaded_by_user->name : '';
        unset($this->uploaded_by_user);
        return $name;
    }
    public function getFilePath(){
        return 'admin_repair_request_form_files';
    }
    public function getRelationId(){
        return 'arrf_id';
    }
    public function getPath(){
        return 'admin/admin_repair_request_form_files';
    }
    public function getDisk(){
        return 'admin_repair_request_form_files';
    }

    public function getFillables($request){
        $this->arrf_id = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name = $request['name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
