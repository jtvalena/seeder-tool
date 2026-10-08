<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SampleFormFiles extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'file_type',
    ];

    public function sample_form()
    {
        return $this->belongsTo(SampleForm::class, 'id');
    }

    public function getFileTypeAttribute()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);;
    }
}
