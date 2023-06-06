<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();


//        $users = User::all();
//        foreach ($users as $userdata){
//            $roles = $userdata->roles[0];
//
//            $roleId = $roles->id;
//
//            $userdata->update(['role_id' => $roleId]);
//        }
//        dd($users);

        if (!app()->runningInConsole() && $user) {
            $myRoleId = $user->role_id;
            $key = 'auth_role_permissions_'.$myRoleId;

            $rolePermissions = Cache::get($key);
            if(!$rolePermissions){
                $rolePermissions = Role::where('id',$myRoleId)->with('permissions')->get();
                Cache::forget($key);
                Cache::add($key,$rolePermissions,now()->addHours(1));
            }

            $permissionsArray = [];
            foreach ($rolePermissions as $role) {
                foreach ($role->permissions as $permissions) {
                    $permissionsArray[$permissions->title] = $permissions->id;
                }
            }

            foreach ($permissionsArray as $title => $id) {
                Gate::define($title, function () {
                    return true;
                });
            }
        }

        return $next($request);
    }
}
