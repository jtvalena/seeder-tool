<?php

namespace App\Models\Operations;

use App\Models\Items\ItemCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WpfGroupCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wpf_group_categories';

    protected $fillable = [
        'wpf_group_id',
        'item_category_id',
    ];

    /**
     * Get the WPF group that owns this category assignment
     */
    public function wpfGroup()
    {
        return $this->belongsTo(WpfGroup::class, 'wpf_group_id', 'id');
    }

    /**
     * Get the item category
     */
    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id');
    }
}



