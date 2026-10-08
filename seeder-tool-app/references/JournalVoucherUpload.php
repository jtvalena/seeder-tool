<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class JournalVoucherUpload extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'journal_voucher_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
        'upload_type',
        'deleted_by',
    ];
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'uploaded_by_name'
    ];

    public function getUploadedByNameAttribute()
    {
        $user = User::where('id', $this->uploaded_by)->first();
        return $user->name;
    }

    public function getFilePath()
    {
        return 'journal_voucher_files';
    }

    public function getRelationId(){
        return 'journal_voucher_id';
    }

    public function getPath(){
        return 'accounting/journal_voucher_files';
    }

    public function getDisk(){
        return 'journal_voucher_files';
    }

    public function getFillables($request){
        $this->journal_voucher_id = $request['m_id'];
        $this->uploaded_by = Auth::id() ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        $this->upload_type = $request['upload_type'] ?? 1;
        return $this;
    }
}
