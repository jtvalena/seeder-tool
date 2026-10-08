<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreBranch extends Model
{
    use HasFactory, SoftDeletes;

    // protected $guarded = ['store_branches'];

    protected $fillable = [
        'code',
        'name',
        'company_id',
        'pv_template_id',
        'company_classification_id',
        'certified_correct_by',
        'address',
        'coa_id',
        'related_vendor_id',
        'advances_coa_id',
        'ap_coa_id',
        'tin',
        'zip_code',
        'latitude',
        'longitude',
        'active',
    ];

    public function pv_template()
    {
        return $this->belongsTo(FormTemplate::class, 'pv_template_id');
    }
}
