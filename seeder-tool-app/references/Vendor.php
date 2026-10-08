<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Vendor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = [ 'created_by_name', 'updated_by_name', 'deleted_by_name'];

    protected $fillable = [
        'code',
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
        'zip_code',
        'tin_number',
        'tax_classification',
    ];

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
