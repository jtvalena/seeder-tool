<?php

declare(strict_types=1);

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserDashboardView extends Model
{
    use HasFactory;

    protected $table = 'user_dashboard_views';

    protected $fillable = [
        'user_id',
        'name',
        'sort_order',
        'number_format_id',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layouts(): HasMany
    {
        return $this->hasMany(UserDashboardLayout::class, 'view_id');
    }
}
