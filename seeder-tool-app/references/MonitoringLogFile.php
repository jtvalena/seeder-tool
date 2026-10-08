<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\IT\MonitoringLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class MonitoringLogFile extends Model
{
    protected $table = 'monitoring_log_files';

    protected $fillable = [
        'monitoring_log_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
    ];

    protected $appends = ['uploaded_by_name'];

    public function monitoringLog(): BelongsTo
    {
        return $this->belongsTo(MonitoringLog::class);
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUploadedByNameAttribute(): string
    {
        return $this->uploadedByUser?->name ?? '';
    }

    public function getRelationId(): string
    {
        return 'monitoring_log_id';
    }

    public function getPath(): string
    {
        return 'it/monitoring_log_files';
    }

    public function getDisk(): string
    {
        return 'monitoring_log_files';
    }

    public function getFillables(array $request): self
    {
        $this->monitoring_log_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = isset($request['extension']) ? strtolower((string) $request['extension']) : '';

        if ($this->extension === '' && $this->name !== '') {
            $parts = explode('.', $this->name);
            if (count($parts) > 1) {
                $this->extension = strtolower((string) end($parts));
            }
        }

        if ($this->type === '' && $this->extension !== '') {
            $this->type = $this->getMimeTypeFromExtension($this->extension);
        }

        return $this;
    }

    private function getMimeTypeFromExtension(string $extension): string
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}
