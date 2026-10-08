<?php

namespace App\Models\Service;

use App\Model\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ScLocation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'contact_number',
        'default',
        'with_tech',
        'drop_off_only',
        'repairing_sc_location_id',
        'handles_large_items',
        'repair_size_capability',
        'deleted_at',
    ];

    protected $casts = [
        'with_tech'              => 'boolean',
        'drop_off_only'          => 'boolean',
        'handles_large_items'    => 'boolean',
        'repair_size_capability' => 'integer',
    ];

    /**
     * Legacy warehouse relationship (pre-sc_location_warehouses era).
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * For drop-off-only branches: the branch that performs the actual repair.
     */
    public function repairingBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'repairing_sc_location_id');
    }

    /**
     * All drop-off branches that route repairs here.
     */
    public function dropOffLocations(): HasMany
    {
        return $this->hasMany(ScLocation::class, 'repairing_sc_location_id');
    }
}
