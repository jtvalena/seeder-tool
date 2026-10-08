<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class JournalVoucherManagerUpload extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'journal_voucher_manager_id',
        'uploaded_by',
        'name',
        'path',
        'type',
        'extension',
        'upload_type',
        'deleted_by',
    ];
    protected $appends = [
        'uploaded_by_name'
    ];
    public function getUploadedByNameAttribute()
    {
        $user = User::where('id', $this->uploaded_by)->first();
        return $user->name;
    }
}
