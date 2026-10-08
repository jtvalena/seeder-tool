<?php

declare(strict_types=1);

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDashboardLayout extends Model
{
    use HasFactory;

    protected $table = 'user_dashboard_layouts';

    protected $fillable = [
        'user_id',
        'view_id',
        'dashboard_id',
        'sort_order',
        'cols',
        'rows',
        'is_visible',
        'color_scheme_id',
        'number_format_id',
    ];

    protected $casts = [
        'is_visible'  => 'boolean',
        'sort_order'  => 'integer',
        'cols'        => 'integer',
        'rows'        => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function view(): BelongsTo
    {
        return $this->belongsTo(UserDashboardView::class, 'view_id');
    }

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class, 'dashboard_id');
    }
}
