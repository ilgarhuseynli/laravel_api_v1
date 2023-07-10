<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Helpers;
use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!Permission::check('user_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $username = $request->username;
        $name = $request->name;
        $role = $request->role;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,User::$sortable);

        $userQuery = User::query();

        if (strlen($name) > 0)
            $userQuery->where('name','like','%'.$name.'%');
        if (strlen($username) > 0)
            $userQuery->where('username','like','%'.$username.'%');
        if ($role)
            $userQuery->where('role_id',$role);

        $usersCount = $userQuery->count();
        $users = $userQuery->orderBy($sort[0],$sort[1])->skip($skip)->take($limit)->get();

        return Res::custom([
            'status'=>'success',
            'data'=> UserResource::collection($users),
            'count'=>$usersCount,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }

    public function minlist(Request $request)
    {
        $name = $request->name;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);

        $userQuery = User::query();

        if (strlen($name) > 0){
            $userQuery->where('name','like','%'.$name.'%');
        }

        $users = $userQuery->skip($skip)->take($limit)->get();
        $usersCount = $userQuery->count();

        return Res::custom([
            'status'=>'success',
            'data'=>$users,
            'count'=>$usersCount,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }


    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return Res::success(['id' => $user->id],'Created successfully');
    }

    public function show(User $user)
    {
        abort_if(!Permission::check('user_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Res::success(new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return Res::success(['id' => $user->id],'Updated successfully');
    }

    public function destroy(User $user)
    {
        abort_if(!Permission::check('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }


    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }
}
