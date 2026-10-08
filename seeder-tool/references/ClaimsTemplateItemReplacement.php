<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsTemplateItemReplacement extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'claims_template_item_id',
        'old_text',
        'new_text',
    ];

    public function claims_template_item()
    {
        return $this->belongsTo(ClaimsTemplateItem::class);
    }
}
