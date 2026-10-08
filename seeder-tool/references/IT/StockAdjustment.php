<?php

namespace App\Models\IT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\IT\StockAdjustmentItem;
use App\Models\Operations\Warehouse;
use App\Models\StockAdjustmentFile;
use App\Models\User;

class StockAdjustment extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['status_text', 'warehouse_name', 'processed_by_name'];

    protected $fillable = [
        'reference_num',
        'warehouse_id',
        'remarks',
        'requestor_id',
        'requestor_name',
        'status',
        'processed_by',
        'processed_date',
        'adjust_stock',
        'adjust_movement'
    ];

    public function stock_adjustment_items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function getStatusTextAttribute()
    {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'confirmed';
        }
    }

    public function warehouse()
    {
        // return $this->belongsTo(Warehouse::class, 'warehouse_id')->withTrashed();
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function processed_by_user()
    {
        // return $this->belongsTo(User::class, 'processed_by')->withTrashed();
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getWarehouseNameAttribute(){
        $warehouse_name = '';
        if(!empty($this->warehouse)){
            $warehouse_name = $this->warehouse->name ?? '';
        }
        unset($this->warehouse);
        return $warehouse_name;
    }

    public function getProcessedByNameAttribute(){
        $name = !empty($this->processed_by_user) ? $this->processed_by_user->name : '';
        unset($this->issuer);
        return $name;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = StockAdjustment::select('reference_num')->whereRaw('length(reference_num)=5')->max('reference_num');
            if (!empty($latest)) {
                $next_reference_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->reference_num = str_pad((string)$next_reference_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->reference_num = str_pad("1", 5, 0, STR_PAD_LEFT);
            }
        });
    }

    public function stock_adjustment_files()
    {
        return $this->hasMany(StockAdjustmentFile::class, 'stock_adjustment_id');
    }
}
