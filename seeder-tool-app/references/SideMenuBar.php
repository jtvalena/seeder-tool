<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SideMenuBar extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'side_menu_bars';
    protected $appends = ['employee_id'];
      /**
     * Get all of the comments for the SideMenuBarLink
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function sidebarlinks()
    {
        // return $this->hasmany(SideMenuBarLink::class,'side_menu_bar_id','id');
        return $this->hasmany(SideMenuBarLink::class);
    }
    public function useraccess(){
        return $this->belongsTo(UserAccess::class,'department_id','department_id');
    }
    public function accesscontrol(){
        return $this->hasMany(AccessControl::class,'menu','id');
    }
    public function getEmployeeIdAttribute(){
        return (int)User::where('id',request()->user_id)->value('employee_id');
    }

}
