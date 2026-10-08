<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class StoreBranchUserAccess extends Model implements Auditable
{   
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    // protected $appends = ['company_ids'];
    protected $fillable = ['user_id','department_id'];

    public function company_id(){
        return $this->hasMany(StoreBranchesAccessCompanyItems::class,'store_branch_user_access_ids')
        ->whereNull('store_branch_user_accesses.deleted_at')
        ->whereNull('companies.deleted_at')
        ->select('store_branch_user_access_ids','store_branches_user_access_company.company_ids','store_branches_user_access_company.id','registered_name')
        ->join('companies','store_branches_user_access_company.company_ids','companies.id')
        ->join('store_branch_user_accesses','store_branches_user_access_company.store_branch_user_access_ids','store_branch_user_accesses.id');
    }
    public function store_branches_ids(){
        return $this->hasMany(StoreBranchUserAccessItems::class,'store_branch_user_access_id');
       
    }
}
