<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class AdminInboundOutboundFile extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_inbound_outbound_files';
    protected $appends = ['uploaded_by_name'];

    protected $fillable = [
        'admin_inbound_outbound_id',
        'name',
        'path',
        'type',
        'uploaded_by',
        'deleted_by',
    ];

    public function getFilePath()
    {
        return 'admin_inbound_outbound_files';
    }

    public function getRelationId(){
        return 'admin_inbound_outbound_id';
    }

    public function getPath(){
        return 'admin/admin_inbound_outbound_files';
    }

    public function getDisk(){
        return 'admin_inbound_outbound_files';
    }

    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->admin_inbound_outbound_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            if($model->admin_inbound_outbound_id){
                AdminInboundOutbound::where('id',$model->admin_inbound_outbound_id)->update(['filed' => 1]);
            }
        });
     
    }
}
