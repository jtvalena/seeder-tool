<?php

namespace App\Models;

use App\Models\User;
use App\Traits\MorphMap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsClaimFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use MorphMap;

    protected $appends = ['uploaded_by_name'];

    public function getUploadedByNameAttribute() {
        $user = User::find($this->uploaded_by);

        return !!$user ? ($user->name == Auth::user()->name ? 'You' : $user->name) : '';
    }

    public function getFilePath()
    {
        return 'claims_claim_files';
    }

    public function getRelationId(){
        return 'claims_claim_id';
    }

    public function getPath(){
        return 'accounting/claims_claim_files';
    }

    public function getDisk(){
        return 'claims_claim_files';
    }

    public function getFillables($request){
        $this->claims_claim_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }
}
