<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReturnFormFiles extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'file_type',
    ];

    public function return_form()
    {
        return $this->belongsTo(ReturnForm::class, 'id');
    }

    public function getFileTypeAttribute()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);;
    }
}
