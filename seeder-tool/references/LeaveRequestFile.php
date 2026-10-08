<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;

class LeaveRequestFile extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'leave_request_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
    ];

    protected $appends = ['uploaded_by_name'];

    public function leave_request(){
        return $this->belongsTo(LeaveRequest::class);
    }

    public function get_uploaded_by_name()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUploadedByNameAttribute(){
        $name = !empty($this->get_uploaded_by_name) ? $this->get_uploaded_by_name->name : '';
        unset($this->get_uploaded_by_name);
        return $name;
    }

    public function getFilePath()
    {
        return 'leave_requests_uploads';
    }

    public function getRelationId(){
        return 'leave_request_id';
    }

    public function getPath(){
        return 'hr/leave_requests_uploads';
    }

    public function getDisk(){
        return 'leave_requests_uploads';
    }

    public function getFillables($request){
        $this->leave_request_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        
        // Get extension from name if not provided
        if (empty($request['extension']) && !empty($this->name)) {
            $parts = explode('.', $this->name);
            if (count($parts) > 1) {
                $this->extension = strtolower(end($parts));
            }
        } else {
            $this->extension = $request['extension'] ?? '';
        }
        
        // Get type from extension if not provided
        if (empty($request['type']) && !empty($this->extension)) {
            $this->type = $this->getMimeTypeFromExtension($this->extension);
        } else {
            $this->type = $request['type'] ?? '';
        }
        
        return $this;
    }

    /**
     * Get MIME type from file extension
     */
    private function getMimeTypeFromExtension($extension)
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
