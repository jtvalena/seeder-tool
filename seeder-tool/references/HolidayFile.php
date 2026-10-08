<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HolidayFile extends Model
{
    use HasFactory;

    public function getFilePath()
    {
        return 'holiday_files';
    }

    public function getRelationId(){
        return 'holiday_id';
    }

    public function getPath(){
        return 'hr/holiday_files';
    }

    public function getDisk(){
        return 'holiday_files';
    }
    
    // public function getUploadedByNameAttribute(){
    //     return User::where('id',$this->uploaded_by)->value('name');
    // }

    protected $appends = ['uploaded_by_name'];
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
        $this->holiday_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
