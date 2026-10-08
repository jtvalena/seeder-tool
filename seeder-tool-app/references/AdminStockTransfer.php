<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\SharedFunctionController;

class AdminStockTransfer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'admin_stock_transfers';
    protected $fillable = [
        'reference_num',
        'from_admin_warehouse_id',
        'to_admin_warehouse_id',
        'remarks',
        'requestor_id',
        'requestor_name',
        'created_by_id',
        'created_by_name',
        'status',
        'approved_by',
        'approved_date',
        'adjust_stock',
        'adjust_movement',
        'quantity',
        'created_at',
        'updated_at'
    ];

    public function stockTransferItems(){
        return $this->hasMany(AdminStockTransferItem::class,'admin_stock_transfer_id');
    }

     public static function boot()
    {
        parent::boot();
        static::created((function ($model) {
            $year = now()->year;

            $latest_ref = AdminStockTransfer::where('reference_num', 'like', "$year-%") // optimize for this year's ref
                ->orderByDesc('id') // ensures latest is checked first
                ->get()
                ->pluck('reference_num')
                ->first(function ($ref) use ($year) {
                    return preg_match("/^$year-\d{4}$/", $ref);
                });
            if (!$latest_ref) {
                $paddedNum = $year . '-0001';
            }else{
                $paddedNum = SharedFunctionController::padForSeries(AdminStockTransfer::class, $model->id, 4, $latest_ref);
            }

            $model->reference_num = $paddedNum;
            $model->save();

        }));
    }
}
