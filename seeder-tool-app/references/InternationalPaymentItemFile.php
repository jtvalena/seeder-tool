<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InternationalPaymentItemFile extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = ['uploaded_by_name'];

    public function getFilePath()
    {
        return 'international_payment_item_files';
    }
    
    public function getRelationId(){
        return 'international_payment_item_id';
    }
    
    public function getPath(){
        return 'accounting/international_payment_item_files';
    }
    
    public function getDisk(){
        return 'international_payment_item_files';
    }
    
    public function uploaded_by_user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    public function getUploadedByNameAttribute(){
        $name = !empty($this->uploaded_by_user) ? $this->uploaded_by_user->name : '';
        unset($this->uploaded_by_user);
        return $name;
    }
    
    public function getFillables($request){
        $this->international_payment_item_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
