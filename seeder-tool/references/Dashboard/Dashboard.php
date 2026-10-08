<?php

declare(strict_types=1);

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dashboard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dashboards';

    protected $fillable = [
        'key',
        'name',
        'description',
        'icon',
        'category',
        'default_cols',
        'default_rows',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'default_cols' => 'integer',
        'default_rows' => 'integer',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(UserDashboardAssignment::class, 'dashboard_id');
    }

    public function layouts(): HasMany
    {
        return $this->hasMany(UserDashboardLayout::class, 'dashboard_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
