<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationFormTemplate extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'payment_term',
        'with_attachment',
        'particular_guide_id',
        'is_disbursed_to_requestor',
    ];

    public function travel_authorization_form_template_items()
    {
        return $this->hasMany(TravelAuthorizationFormTemplateItem::class, 'taf_template_id','id')->orderByRaw("FIELD(RIGHT(data_type_id, 1), '4') ASC");
    }
    public function templateItems()
    {
        return $this->hasMany(TravelAuthorizationFormTemplateItem::class, 'taf_template_id');
    }
    /**
     * Get the particulars guide assigned to the template.
     */
    public function particularGuide()
    {
        return $this->belongsTo(ParticularsGuide::class, 'particular_guide_id');
    }
    public function templateFiles()
    {
        return $this->hasMany(TravelAuthorizationFormTemplateFile::class, 'travel_authorization_form_template_id');
    }
}
