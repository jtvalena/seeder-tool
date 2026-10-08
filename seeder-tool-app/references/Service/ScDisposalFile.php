<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScDisposalFile extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'path',
        'uploaded_by',
        'deleted_by',
        'disposal_id',
        'position',
    ];

    public function scdisposal() {
        return $this->belongsTo(ScDisposal::class);
    }

}
