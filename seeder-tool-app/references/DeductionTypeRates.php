<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeductionTypeRates extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'deduction_type_id',
        'fixed',
        'bracket_table',
        'rate',
        'year',
        'max_salary_amount',
    ];

    protected $appends = [
        'matrix_table'
    ];

    public function getMatrixTableAttribute(){
        if(!is_null($this->bracket_table)){
            $table_data = DB::table($this->bracket_table)->get();
            return $table_data;
        }
        return [];
    }
}
