<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!Permission::check('permission_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = $request->user_id;
        $canViewAll = Permission::check('permission_view','all');

        if (!$userId && !$canViewAll){
            $userId = Auth::id();
        }

        if ($userId != Auth::id() && !$canViewAll){
            Res::error('PermissionNotAllowed','PermissionNotAllowed');
        }

        //if perm view all then can see all users perms.
        $userData = User::find($userId);

        if (!$userData){
            Res::error('DataNotFound','DataNotFound');
        }

        $permList = $userData->getPermissions();

        if ($request->formated){
            $permList = Permission::formatList($permList);
        }

        return Res::success($permList);
    }


    public function update()
    {
        abort_if(Gate::denies('permission_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //if perm update all then can see all users perms.


    }

}
