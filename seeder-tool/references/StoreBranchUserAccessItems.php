<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBranchUserAccessItems extends Model
{
    use HasFactory;

    protected $fillable = ['store_branch_user_access_id','store_branches_id'];

}
