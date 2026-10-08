<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TravelAuthorizationFormFile extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = ['uploaded_by_name'];
    public function getFilePath()
    {
        return 'travel_authorization_form_files';
    }

    public function getRelationId(){
        return 'taf_id';
    }

    public function getPath(){
        return 'accounting/travel_authorization_form_files';
    }

    public function getDisk(){
        return 'travel_authorization_form_files';
    }

    public function getUploadedByNameAttribute(){
        return User::where('id',$this->uploaded_by)->value('name');
    }

    public function getFillables($request){
        $this->taf_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
