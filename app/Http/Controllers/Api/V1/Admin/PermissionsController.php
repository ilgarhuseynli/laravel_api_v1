<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!Permission::check('permission_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = $request->user_id;
        $canViewAll = Permission::check('permission_view', 'all');

        if (!$userId && !$canViewAll) {
            $userId = Auth::id();
        }

        if ($userId != Auth::id() && !$canViewAll) {
           return Res::error('PermissionNotAllowed', 'PermissionNotAllowed');
        }

        //if perm view all then can see all users perms.
        $userData = User::findOrFail($userId);

        if (!$userData) {
           return Res::error('DataNotFound', 'DataNotFound');
        }

        $permList = $userData->getPermissions();

        if ($request->formated) {
            $permList = Permission::formatList($permList);
        }

        return Res::success($permList);
    }


    public function update(Request $request)
    {
        abort_if(!Permission::check('permission_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = $request->user_id;
        $canViewAll = Permission::check('permission_view', 'all');

        if (!$canViewAll) {
           return Res::error('PermissionNotAllowed', 'PermissionNotAllowed');
        }
        if ($userId == Auth::id()) {
            return Res::error('CantUpdateSelf', 'CantUpdateSelf');
        }

        $userData = User::findOrFail($userId);

        if (!$userData) {
           return Res::error('DataNotFound', 'DataNotFound');
        }

        $defaultUserPermList = Permission::getByRole($userData->role_id);

        $permExist = $defaultUserPermList[$request->value];

        if (!$permExist) {
           return Res::error('PermissionNotExists', 'PermissionNotExists');
        }

        $resData = $permExist;

        $userPermissions = $userData->permissions;
        if ($request->locked) {
            $userPermissions[$request->value] = $request->allow;
            $resData['allow'] = $request->allow;
            $resData['locked'] = true;
        } else {
            unset($userPermissions[$request->value]);
            $resData['locked'] = false;
        }

        $userData->update(['permissions' => $userPermissions]);

        $resData = [
            'allow' =>$resData['allow'],
            'locked' =>$resData['locked'],
            'title' =>$resData['title'],
            'value' =>$resData['value'],
        ];

        return Res::success($resData, 'Updated successfully');
    }

}
