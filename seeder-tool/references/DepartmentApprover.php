<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentApprover extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'module',
        'user_id',
        'user_department_id',
        'access_department_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'int',
        'user_department_id' => 'int',
        'access_department_id' => 'int',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'user_department_id');
    }

    public function accessDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'access_department_id');
    }

    public function transactType(): BelongsTo
    {
        return $this->belongsTo(TransactType::class, 'module', 'model_name');
    }
}
