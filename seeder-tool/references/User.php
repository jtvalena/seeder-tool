<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\AccessControl;
use App\Models\MasterData\Customer;
use App\Models\Service\ScLocation;
use App\Traits\Caching;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes, Caching;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'code',
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'department_id',
        'mobile_no',
        'active',
        'locked',
        'profile_url',
        'manager',
        'super_admin',
        'job_title',
        'employee_id',
        'change_password_next',
        'password_expiration',
        'token_expiration_time',
        'customer_id',
        'sc_location_id',
    ];
    protected $appends = [
        'mobile_number',
        'customer_name',
        'employee_data'
    ];
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
    public function scLocation()
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function check_access(){
        return $this->hasMany(AccessControl::class,'user_id','id');
    }

    public function userAccess(){
        return $this->hasMany(UserAccess::class,'user_id','id');
    }

    public function customers_include()
    {
        return $this->hasMany(UserCustomerAccess::class)->where('exclude', 0);
    }

    public function customers_exclude()
    {
        return $this->hasMany(UserCustomerAccess::class)->where('exclude', 1);
    }
    public function getMobileNumberAttribute()
    {
        $mobileNumbers = EmployeeMobileNumber::where('employee_id', $this->employee_id)
            ->pluck('mobile_number')
            ->toArray();

        return implode('/', $mobileNumbers);
    }

    public function is_technician_admin() {
        if($this->technician_admin == 1) {
            return true;
        }

        return false;
    }

    // private function checkAccessControl($is_main, $the_user, $department_code, $module, $action){
    //     $has_access = false;
    //     if( $is_main ){
    //         $access_control = AccessControl::where('user_id', $the_user->id)->where('department', $department_code)->where('active', 1)->first();
    //         $access_json        = null;

    //         if( !empty($access_control->access_control) ){
    //             $access_json = json_decode($access_control->access_control, TRUE);
    //         }
    //         if( !empty($access_json) ){
    //             if( !empty($access_json['view']) ){
    //                 $has_access = true;
    //             }
    //         }
    //     }else{
    //         $access_control = AccessControl::select('access_control')->where('user_id', $the_user->id)->where('department', $department_code)->where('active', 1)->first();

    //         $access_json        = null;

    //         if( !empty($access_control) ){
    //             $access_json = json_decode($access_control->access_control, TRUE);
    //         }

    //         if( !empty($access_json) ){
    //             if( !empty($access_json[$module][$action]) ){
    //                 $has_access = true;
    //             }
    //         }elseif($department_code == 'SM'){
    //             $access_control = AccessControl::select('access_control')->where('user_id', $the_user->id)->where('department', 'ST')->where('active', 1)->first();
    //             if( !empty($access_control) ){
    //                 $access_json = json_decode($access_control->access_control, TRUE);
    //             }
    //             if( !empty($access_json) ){
    //                 if( !empty($access_json[$module][$action]) ){
    //                     $has_access = true;
    //                 }
    //             }
    //         }elseif($department_code == 'AU'){
    //             $access_control = AccessControl::select('access_control')->where('user_id', $the_user->id)->where('department', 'AU')->where('active', 1)->first();
    //             if( !empty($access_control) ){
    //                 $access_json = json_decode($access_control->access_control, TRUE);
    //             }

    //             if(!empty($access_json)){
    //                 if(!empty($access_json[$module][$action])){
    //                     $has_access = true;
    //                 }
    //             }
    //         }
    //     }

    //     return !empty($has_access)?$has_access:false;
    // }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'users_department_dropdown',
            'users_dropdown',
            'managers_dropdown',
            'user_list_dropdown',
            'accounting_users',
            'hr_users_selection',
            'current_user',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    public function getCustomerNameAttribute()
    {
        $customerName = Customer::getQuery()->where('id', $this->customer_id)
            ->first();

        return $customerName;
    }
    public function getEmployeeDataAttribute()
    {
        $employeeData = EmployeeData::where('user_id', $this->id)
            ->select(
                'id',
                'user_id',
                'employee_id',
                'first_name',
                'last_name',
                'real_employee_id',
                'employee_superior_id',
                'department_id',
                'client_id',
                'contract_type_id',
                'company_id',
                'middle_name',
                'citizenship_id',
                'religion_id',
                'marital_status_id',
                'job_title_id',
                'store_branch_id',
                'status_id',
                'job_level_id',
                'salary_type_id'
            )
            ->first();
        if (!$employeeData) {
            return null;
        }
        return $employeeData->getAttributes();
    }
}
