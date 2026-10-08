<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRepairRequestFormItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin_repair_request_form_items';
    protected $guarded = [];

    public function admin_repair_request(){
        return $this->belongsTo(AdminRepairRequestForm::class,'id');
    }

    public static function boot()
    {
        parent::boot();
        static::updated((function ($item){
            $parent = AdminRepairRequestForm::find($item->arrf_id);
            if (!empty($parent)) {
                // If at least one item has a JO created, set parent status to 4
                $hasAnyJoCreated = self::where('arrf_id', $parent->id)
                    ->where('jo_created', 1)
                    ->exists();
                if ($hasAnyJoCreated) {
                    $parent->status = 4;
                    $parent->saveQuietly();
                }

                $totalItems = self::where('arrf_id', $parent->id)->count();
                $cancelledItems = self::where('arrf_id', $parent->id)
                    ->where('cancelled', 1)
                    ->count();

                if ($totalItems > 0 && $totalItems == $cancelledItems) {
                    $parent->status = 6;
                    $parent->saveQuietly();
                }
            }
        }));
    }
}
