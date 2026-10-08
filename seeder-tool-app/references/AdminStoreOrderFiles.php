<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminStoreOrderFiles extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use HasFactory,SoftDeletes;
    
    protected $table = 'admin_store_order_files';
    protected $fillable = [
        'store_order_id',
        'name',
        'path',
        'upload_by',
        'extension',
        'type',
        'upload_type',
        'store_order_item_id'
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
    public function getRelationId(){
        return 'store_order_id';
    }
    public function getPath(){
        return 'admin/store_order_file_uploads';
    }
    public function getDisk(){
        return 'store_order_file_uploads';
    }
    public function getFilePath()
    {
        return 'store_order_file_uploads';
    }
    public function getFillables($request){
        $this->store_order_id = $request['m_id'];
        $this->upload_by = \Illuminate\Support\Facades\Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->upload_type = 2;
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
