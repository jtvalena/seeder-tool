<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsTemplateItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'claims_template_id',
        'base_field',
        'file_field',
        'readonly',
    ];

    public function claims_template()
    {
        return $this->belongsTo(ClaimsTemplate::class);
    }

    public function replacements()
    {
        return $this->hasMany(ClaimsTemplateItemReplacement::class);
    }
}
