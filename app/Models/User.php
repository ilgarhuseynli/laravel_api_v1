<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Classes\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    public $table = 'users';

    protected $fillable = [
        "username",
        "surname",
        'name',
        'email',
        "phone",
        "address",
        "role_id",
        "permissions",
        'password',
        'deleted_at',
    ];

    public static $sortable = [
        "id",
        "username",
        "surname",
        'name',
        'email',
        'created_at',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
    ];

    const ROLE_MODERATOR = 1;
    const ROLE_EMPLOYEE = 2;
    const ROLE_USER = 3;


    public function getPermissions(){
        $currentUserPerms = (array)$this->permissions;
        $currentUserRole = $this->role_id;

        //find default perms
        $permListByKey = Permission::getByRole($currentUserRole);

        foreach ($currentUserPerms as $key=> $val){
            if ($permListByKey[$key]){
                $permListByKey[$key]['allow'] = $val;
                $permListByKey[$key]['locked'] = true;
            }
        }

        return $permListByKey;
    }


    public static function checkPermission($role,$permission = 'view')
    {
        $permissions = Auth::user()->getPermissions();

        $allow = false;
        if ($role == User::ROLE_MODERATOR && $permissions['moderator_'.$permission]['allow']) {
            $allow = true;
        } elseif ($role == User::ROLE_EMPLOYEE && $permissions['employee_'.$permission]['allow']) {
            $allow = true;
        } elseif ($role == User::ROLE_USER && $permissions['user_'.$permission]['allow']) {
            $allow = true;
        }

        return $allow;
    }


}
