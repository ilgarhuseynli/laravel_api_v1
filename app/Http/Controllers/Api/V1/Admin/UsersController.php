<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Helpers;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $username = $request->username;
        $name = $request->name;
        $role = $request->role;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,User::$sortable);

        if (!User::checkPermission($role))
            return Res::error('Permission not allowed');

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
        $role = $request->role;
        $username = $request->username;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);

        $userQuery = User::query();

        if (strlen($name) > 0)
            $userQuery->where('name','like','%'.$name.'%');
        if (strlen($username) > 0)
            $userQuery->where('username','like','%'.$username.'%');
        if ($role)
            $userQuery->where('role_id',$role);

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
        if (!User::checkPermission($user->role_id,'create'))
            return Res::error('Permission not allowed');

        return Res::success(new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return Res::success(['id' => $user->id],'Updated successfully');
    }


    public function updatePassword(Request $request)
    {
        $userId = $request->id;

        if (!$userId)
            return Res::error('User not found');

        $userData = User::findOrFail($request->id);

        if (!User::checkPermission($userData->role_id,'update'))
            return Res::error('Permission not allowed');

        $validated = $request->validate([
            'password' => [
                'required',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
        ]);

        $userData->update($validated);

        return Res::success(['id' => $userData->id],'Updated successfully');
    }

    public function destroy(User $user)
    {
        if (!User::checkPermission($user->role_id,'delete'))
            return Res::error('Permission not allowed');

        $user->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }
}
