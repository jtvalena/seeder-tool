<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PettyCashItemFile extends Model
{
    use HasFactory,SoftDeletes;
    protected $appends = ['uploaded_by_name'];

    public function getFilePath()
    {
        return 'petty_cash_item_files';
    }
    public function getRelationId(){
        return 'petty_cash_item_id';
    }
    public function getPath(){
        return 'accounting/petty_cash_item_files';
    }
    public function getDisk(){
        return 'petty_cash_item_files';
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
        $this->petty_cash_item_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        $this->payment_voucher_id = $request['payment_voucher_id'] ?? null;
        $this->reference_id = $request['reference_id'] ?? null;
        return $this;
    }
}
