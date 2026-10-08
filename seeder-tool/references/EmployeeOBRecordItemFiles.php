<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOBRecordItemFiles extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    protected $table = 'employee_ob_record_item_files';

    protected $fillable = [
        'ob_record_item_id',
        'file_path',
        'name',
    ];

    public function employee_ob_record_items(){
        $this->belongsTo(EmployeeOBRecordItems::class);
    }
}
