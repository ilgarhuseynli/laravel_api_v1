<?php

namespace App\Models;

use App\Classes\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable,InteractsWithMedia;

    public $table = 'users';

    protected $fillable = [
//        "username",
        "keyword",
        "surname",
        'name',
        'email',
        "phone",
        "role_id",
        "permissions",
        "address_list",
        'password',
        'deleted_at',
    ];

    protected $appends = [
        'avatar',
    ];

    public static $sortable = [
        "id",
//        "username",
        "surname",
        "phone",
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
        'address_list' => 'array',
    ];

    const ROLE_MODERATOR = 1;
    const ROLE_EMPLOYEE = 2;
    const ROLE_USER = 3;

    const ROLES_LIST = [
        self::ROLE_MODERATOR => 'moderator',
        self::ROLE_EMPLOYEE => 'employee',
        self::ROLE_USER => 'user',
    ];

    public static function getRoleList($val = false){
        $list = [
            ['value' => self::ROLE_MODERATOR, 'label' => 'Moderator', ],
            ['value' => self::ROLE_EMPLOYEE, 'label' => 'Employee', ],
            ['value' => self::ROLE_USER, 'label' => 'User', ],
        ];

        return $val ? $list[$val - 1] : $list;
    }

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


    public function getAvatarAttribute()
    {
        return $this->getMedia('avatar')->last();
    }

    public function getFullnameAttribute()
    {
        return $this->name.' '.$this->surname;
    }


    public static function filterResponse($user){
        if (!$user)
            return false;

        return [
            'label' => $user->name.' '.$user->surname,
            'value' => $user->id,
            'fullname' => $user->name.' '.$user->surname,
            'name' => $user->name,
            'surname' => $user->surname,
            'phone' => $user->phone,
            'email' => $user->email,
            'role' => self::getRoleList($user->role_id),
            'address_list' => $user->address_list,
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
//        $this
//            ->addMediaConversion('preview')
//            ->fit(Manipulations::FIT_CROP, 300, 300)
//            ->nonQueued();

        $this->addMediaConversion('thumb')->width(150)->height(150)->performOnCollections('avatar');

        $this->addMediaConversion('medium')->width(600)->height(600)->performOnCollections('avatar');

        $this->addMediaConversion('large')->width(1000)->height(1000);
    }

    public static function joinKeywords($request){
        return $request->name.' '.$request->surname.' '.$request->phone.' '.$request->email;
    }
}
