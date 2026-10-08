<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ServiceOrderRequestFile extends Model
{
    use SoftDeletes;

    protected $table = 'service_order_request_files';

    protected $fillable = [
        'sor_id',
        'uploaded_by',
        'name',
        'path',
        'extension',
    ];

    protected $appends = ['uploaded_by_name'];

    public function getUploadedByNameAttribute(): string
    {
        return User::find($this->uploaded_by)?->name ?? '';
    }

    public function sor()
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }

    public function getPath(): string
    {
        return 'service/sor_files';
    }

    public function getDisk(): string
    {
        return 'sor_files';
    }

    public function getRelationId(): string
    {
        return 'sor_id';
    }

    public function getFillables(array $request): static
    {
        $this->sor_id      = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name        = $request['file_name'] ?? '';
        $this->path        = $request['filePath'] ?? '';
        $this->extension   = $request['extension'] ?? '';

        return $this;
    }
}
