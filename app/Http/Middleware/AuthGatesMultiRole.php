<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AuthGatesMultiRole
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!app()->runningInConsole() && $user) {
            $myUserId = $user->id;
            $key = 'auth_user_permissions_'.$myUserId;


            $rolePermissions = Cache::get($key);
            if(!$rolePermissions){
                $rolePermissions = Role::whereIn('id',$user->roles->pluck('id'))->with('permissions')->get();
                Cache::forget($key);
                Cache::add($key,$rolePermissions,now()->addMinutes(10));
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
