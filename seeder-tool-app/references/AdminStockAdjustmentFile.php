<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AdminStockAdjustmentFile extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = 'admin_stock_adjustment_files';

    protected $appends = ['uploaded_by_name'];


    public function getUploadedByNameAttribute(){
        return User::find($this->uploaded_by)->name ?? '';
    }
}
