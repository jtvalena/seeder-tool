<?php
namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Logistics\Dispatch;
use App\Models\Sales\PackingList;

use OwenIt\Auditing\Contracts\Auditable;

class DispatchItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'dispatch_id',
        'packing_list_id',
        'quantity',
        'pl_ids',
        'customer_id',
    ];

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function packing_list_item()
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id');
    }

    public function dispatch_item_wpfs()
    {
        return $this->hasMany(DispatchItemWpf::class);
    }

    public function getPackingListsAttribute(){
        if(!empty($this->pl_ids)){
            $packing_lists = PackingList::select('id as value', 'customer_id', 'packing_list_num as text', 'packing_list_num')->whereIn('id', json_decode($this->pl_ids))->get();
            return $packing_lists;
        }else{
            return [];
        }
    }
}
