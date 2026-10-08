<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Report extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'reports';

    protected $guarded = [];

    public function childs(){
        return $this->hasMany(Report::class,'group_id');
    }
    public function children(){
        $user = Auth::user();
        $action = null;
        if($user->president == 0 || $user->super_admin == 0) {
            $menu_id = SideMenuBarLink::where('side_menu_bar_id', 10)->where('department_id', 36)->pluck('id');
            $actions_id = AccessControl::whereIn('menu', $menu_id)->where('user_id', $user->id)->pluck('actions_id');
            $action = Actions::whereIn('id', $actions_id)->pluck('actions');
        }
        return $this->childs()->join('report_categories','reports.report_category_id','report_categories.id')
        ->join('side_menu_bar_links', 'report_categories.side_menu_bar_link_id' ,'side_menu_bar_links.id')
        ->join('icons','reports.icon_id','icons.id')
        ->select([
            'report_categories.name as report_category_name',
            'report_categories.id as report_category_id',
            'reports.name as title',
            'icons.name as icon',
            'reports.code',
            'reports.route',
            'reports.access',
            'reports.active',
            'reports.group_id',
            'side_menu_bar_links.route_name as report_category_route',
        ])
        ->when(!is_null($action), function($q) use ($action) {
            return $q->whereIn('reports.access',$action);
        })        
        ->where('reports.active', 1)
        ->whereNull('reports.deleted_at');
    }
}
