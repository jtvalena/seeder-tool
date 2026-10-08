<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationFormTemplateList extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'travel_authorization_form_template_id',
        'travel_authorization_form_id',
    ];

    public function travel_authorization_form_items()
    {
        return $this->hasMany(TravelAuthorizationFormItem::class, 'travel_authorization_form_template_list_id');
    }
    public function template()
    {
        return $this->belongsTo(TravelAuthorizationFormTemplate::class, 'travel_authorization_form_template_id');
    }
}
