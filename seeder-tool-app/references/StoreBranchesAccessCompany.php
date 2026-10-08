<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBranchesAccessCompany extends Model
{
    use HasFactory;
    protected $table = 'store_branches_user_access_company';
    protected $fillable = ['store_branch_user_access_ids','company_ids'];

    public function company_names(){
        return $this->belongsTo(Company::class,'company_ids','id');
    }
}
