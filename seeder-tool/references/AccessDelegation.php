<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\EmployeeData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Model that maps delegated transaction access between employees.
 */
class AccessDelegation extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'access_delegations';

    protected $fillable = [
        'employee_id',
        'parent_id',
        'user_id',
    ];

    /**
     * Retrieve the employee who grants the delegation.
     */
    public function parentEmployee(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'parent_id');
    }

    /**
     * Retrieve the employee who receives the delegation.
     */
    public function childEmployee(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    /**
     * Retrieve direct child delegations for the parent employee.
     */
    public function child(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Recursively eager load delegation descendants.
     */
    public function children(): HasMany
    {
        return $this->child()->with(['children']);
    }
}
