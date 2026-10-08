<?php
namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Items\Item;
use Illuminate\Support\Facades\DB;

class ItemSpareParts extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'item_id',
        'sp_number',
        'part_number',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function childs(){
        return $this->hasMany(ItemSpareParts::class, 'item_id', 'item_id')->join('items', 'items.id', 'item_spare_parts.part_number')
        ->select([
            'item_spare_parts.item_id',
            'items.model',
            'items.name',
            'item_spare_parts.part_number',
            'items.baklas_qty',
            'items.baklas_qty as default_qty',
            DB::raw("CONCAT(item_spare_parts.item_id, ' - ', item_spare_parts.part_number) as unique_key"),
        ]);
    }

    public function children(){
        return $this->childs();
    }

}
