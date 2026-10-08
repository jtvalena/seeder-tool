<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class ContainerFiles extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $appends = ['uploaded_by_name', 'deleted_by_name'];

    protected $fillable = [
        'name',
        'path',
        'container_id',
        'uploaded_by',
        'deleted_by',
        'group',
        'type',
        'extension',
    ];

    public function getFilePath()
    {
        return 'container_files';
    }

    public function getRelationId(){
        return 'container_id';
    }

    public function getPath(){
        return 'operations/containers';
    }

    public function getDisk(){
        return 'containers';
    }

    public function getUploadedByNameAttribute(): ?string
    {
        return User::where('id', $this->uploaded_by)->value('name');
    }

    public function getDeletedByNameAttribute(): ?string
    {
        if (! $this->deleted_by) {
            return null;
        }

        return User::where('id', $this->deleted_by)->value('name');
    }

    public function getFillables($request){
        $this->container_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        $this->group = $request['group'] ?? '';
        return $this;
    }
}
