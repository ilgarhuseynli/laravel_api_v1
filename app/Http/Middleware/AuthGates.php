<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!app()->runningInConsole() && $user) {

            $userPerms = $user->getPermissions();

            $permissionsArray = [];
            foreach ($userPerms as $key => $val) {
                $permissionsArray[$key] = $val['allow'];
            }

            foreach ($permissionsArray as $title => $val) {
                Gate::define($title, function ($type = false) use ($val) {
                    if ($type){
                        return $type == $val;
                    }
                    return $val;
                });
            }
        }

        return $next($request);
    }

}
