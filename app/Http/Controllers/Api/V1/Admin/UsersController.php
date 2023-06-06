<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Helpers;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with(['roles'])->get();

        return Res::success(UserResource::collection($users));
    }

    public function minlist(Request $request)
    {
        $name = $request->name;
        $types = (array)$request->types;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);

        $userQuery = User::query();

        if (strlen($name) > 0){
            $userQuery->where('name','like','%'.$name.'%');
        }

        if (count($types) > 0){
            $userQuery->whereIn('roles',$types);
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
        $user->roles()->sync($request->input('roles.*.id', []));

        return Res::success(['id' => $user->id],'Created successfully');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource($user->load(['roles']));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        $user->roles()->sync($request->input('roles.*.id', []));

        return Res::success(['id' => $user->id],'Updated successfully');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }


    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }
}
