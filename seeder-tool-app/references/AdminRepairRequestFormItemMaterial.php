<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRepairRequestFormItemMaterial extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'admin_repair_request_form_item_materials';
    protected $guarded = [];
}
