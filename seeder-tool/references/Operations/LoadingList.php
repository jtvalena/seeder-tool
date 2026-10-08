<?php

namespace App\Models\Operations;

use App\Traits\MorphMap;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoadingList extends Model implements Auditable
{
    use HasFactory, SoftDeletes, MorphMap;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [ 'has_container', 'created_by_name', 'updated_by_name', 'deleted_by_name', 'completed', 'status_text' ];

    protected $fillable = [
        'container_num',
        'remarks',
        'etd',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function loading_list_items()
    {
        return $this->hasMany(LoadingListItem::class, 'loading_list_id');
    }

    public function containers()
    {
        return $this->hasMany(Container::class, 'container_num', 'container_num');
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

    public function getHasContainerAttribute()
    {
        return $this->containers()->count() ?? 0;
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

    public function getCompletedAttribute()
    {
        return $this->containers()->whereIn('status', [0, 1, 2])->count() > 0 ? true : false;
    }

    public function getStatusTextAttribute()
    {
        /*
            statuses
            0 = 'pending'
            1 = 'approved'
            2 = 'completed'
            3 = 'rejected'
            4 = 'canceled'
            5 = 'deleted'
        */

        if($this->status==0){
            return 'pending';
        }elseif($this->status==1){
            return 'approved';
        }elseif($this->status==2){
            return 'completed';
        }elseif($this->status==3){
            return 'rejected';
        }elseif($this->status==4){
            return 'canceled';
        }elseif($this->status==5){
            return 'deleted';
        }
    }
}
