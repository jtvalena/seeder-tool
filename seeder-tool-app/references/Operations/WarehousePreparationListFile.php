<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;

class WarehousePreparationListFile extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['uploaded_by_name'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'name',
        'path',
        'type',
        'extension',
        'uploaded_by',
        'deleted_by',
        'wp_list_id',
    ];

    public function warehouse_preparation_list()
    {
        return $this->belongsTo(WarehousePreparationList::class);
    }

    public function getRelationId(): string
    {
        return 'wp_list_id';
    }

    public function getPath(): string
    {
        return 'operations/wpf_uploads';
    }

    public function getDisk(): string
    {
        return 'wpf_uploads';
    }

    public function getUploadedByNameAttribute()
    {
        return User::where('id', $this->uploaded_by)->value('name');
    }

    public function getFillables($request)
    {
        $this->wp_list_id  = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name        = $request['file_name'] ?? '';
        $this->path        = $request['filePath'] ?? '';
        $this->type        = $request['type'] ?? '';
        $this->extension   = $request['extension'] ?? '';

        return $this;
    }
}
