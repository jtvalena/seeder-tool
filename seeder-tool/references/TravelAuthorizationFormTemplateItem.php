<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationFormTemplateItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'taf_template_id',
        'field_name',
        'data_type_id',
        'default_value',
        'column_name',
        'is_array',
        'dropdown_dispatch_id',
        'required',
        'particular_guide_id',
    ];

    public function data_type_items()
    {
        return $this->hasOne(DataType::class);
    }
    public function particulars_guide()
    {
        return $this->belongsTo(ParticularsGuide::class, 'particular_guide_id');
    }
}
