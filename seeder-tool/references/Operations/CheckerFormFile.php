<?php

namespace App\Models\Operations;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CheckerFormFile extends Model implements Auditable
{
   use SoftDeletes;
   use \OwenIt\Auditing\Auditable;

   protected $fillable = [
    'name',
    'path'
   ];

   public function checkerform() 
   {
        return $this->belongsTo(CheckerForm::class);
   }
}
