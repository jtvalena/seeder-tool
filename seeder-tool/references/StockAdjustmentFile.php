<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class StockAdjustmentFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['uploaded_by_name'];


    public function getUploadedByNameAttribute(){
        return User::find($this->uploaded_by)->name ?? '';
    }
}
