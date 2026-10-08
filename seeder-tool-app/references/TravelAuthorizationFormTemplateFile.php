<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationFormTemplateFile extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'travel_authorization_form_id',
        'travel_authorization_form_template_id',
        'name',
        'path',
        'extension',
        'type',
        'uploaded_by',
    ];

    protected $appends = ['uploaded_by_name'];
    public function getFilePath()
    {
        return 'travel_authorization_form_template_files';
    }
    public function getRelationId(){
        return 'travel_authorization_form_template_id';
    }
    public function getPath(){
        return 'accounting/travel_authorization_form_template_files';
    }
    public function getDisk(){
        return 'travel_authorization_form_template_files';
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
        $this->travel_authorization_form_template_id = $request['m_id'];
        $this->travel_authorization_form_id = $request['travel_authorization_form_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
