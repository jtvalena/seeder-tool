<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PaymentAndLiquidationFormFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_and_liquidation_form_id',
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by',
        'deleted_by',
    ];
    public function getFilePath()
    {
        return 'payment_and_liquidation_form_files';
    }

    public function getRelationId(){
        return 'payment_and_liquidation_form_id';
    }

    public function getPath(){
        return 'admin/payment_and_liquidation_form_files';
    }

    public function getDisk(){
        return 'payment_and_liquidation_form_files';
    }

    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->payment_and_liquidation_form_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
