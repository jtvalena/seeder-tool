<?php

namespace App\Models\Operations;

use App\Models\Items\ItemCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WpfGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wpf_groups';

    protected $fillable = [
        'code',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all categories assigned to this WPF group
     */
    public function categories()
    {
        return $this->hasMany(WpfGroupCategory::class, 'wpf_group_id', 'id');
    }

    /**
     * Get item categories through the pivot table
     */
    public function itemCategories()
    {
        return $this->hasManyThrough(
            ItemCategory::class,
            WpfGroupCategory::class,
            'wpf_group_id', // Foreign key on wpf_group_categories table
            'id',           // Foreign key on item_categories table
            'id',           // Local key on wpf_groups table
            'item_category_id' // Local key on wpf_group_categories table
        );
    }
}



