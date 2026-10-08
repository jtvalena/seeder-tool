<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ItemFile extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'item_id',
        'name',
        'path',
        'uploaded_by',
        'deleted_by',
    ];

    protected $appends = [
        'uploaded_by_name'
    ];

    public function getUploadedByNameAttribute(){
        $user = User::find($this->uploaded_by);
        return isset($user) ? $user->name : '';
    }
}
