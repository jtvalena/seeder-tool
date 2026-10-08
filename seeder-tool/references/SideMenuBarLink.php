<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class SideMenuBarLink extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'side_menu_bar_links';
    protected $appends = ['access','all','employee_id'];
    public function __construct()
    {
        $user_id = $this->user_id;
        $department_id = $this->department_id;
    }
    public function actions(){
        return $this->hasMany(Actions::class,'menu','id');
    }
    public function sidemenubars(){
        return $this->hasMany(SideMenuBar::class,'id','side_menu_bar_id');
    }
    public function accesscontrol(){
        return $this->hasMany(AccessControl::class,'menu','id');
    }
    // public function checkhaveaccess(){
    //     AccessControl::where('user_id',$this->user_id);

    // }

    public function getAccessAttribute(){
        return DB::table('accesscontrols')
        ->where('user_id',request()->user_id)
        ->where('menu',$this->id)
        ->where('access_type',2)
        ->pluck(DB::raw('CONCAT(actions_id,";",actions_code)'))
        ->toArray();
    }

    public function getAllAttribute(){
        
        $actions = DB::table('actions')
        ->where('menu',$this->id)->count();
       

        $accesscontrols = DB::table('accesscontrols')
        ->where('menu',$this->id)->where('user_id',request()->user_id)
        ->where('access_type',2)->count();

        return $actions == $accesscontrols;
    }


    public function getEmployeeIdAttribute(){
        return (int)User::where('id',request()->user_id)->value('employee_id');
    }
}
