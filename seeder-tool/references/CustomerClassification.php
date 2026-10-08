<?php

namespace App\Models;

use App\Models\MasterData\CustomerCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerClassification extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [ 'created_by_name', 'updated_by_name', 'deleted_by_name'];

    protected $fillable = [
        'code',
        'name',
        'customer_category_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function customer_category()
    {
        return $this->belongsTo(CustomerCategory::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getCreatedByNameAttribute()
    {
        return $this->created_by_user->name;
    }

    public function getUpdatedByNameAttribute()
    {
        return $this->updated_by_user->name;
    }

    public function getDeletedByNameAttribute()
    {
        return $this->deleted_by_user->name ?? '';
    }
}
