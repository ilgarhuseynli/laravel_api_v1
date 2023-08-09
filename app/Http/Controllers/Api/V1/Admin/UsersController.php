<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\File;
use App\Classes\Helpers;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
//        DB::connection()->enableQueryLog();

        $keyword = $request->keyword;
        $name = $request->name;
        $role = $request->role;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,User::$sortable);

        if (!User::checkPermission($role))
            return Res::error('Permission not allowed');

        $userQuery = User::with('media');

        if (strlen($name) > 0)
            $userQuery->where('name','like','%'.$name.'%');
        if (strlen($keyword) > 0)
            $userQuery->where('keyword','like','%'.$keyword.'%');
        if ($role)
            $userQuery->where('role_id',$role);

        $usersCount = $userQuery->count();
        $users = $userQuery->orderBy($sort[0],$sort[1])->skip($skip)->take($limit)->get();


//        $queries = DB::getQueryLog();

        return Res::custom([
            'status'=>'success',
            'data'=> UserResource::collection($users),
            'count'=>$usersCount,
//            'queries'=>$queries,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }

    public function minlist(Request $request)
    {
        $keyword = $request->keyword;
        $name = $request->name;
        $role = $request->role;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);

        $userQuery = User::query();

        if (strlen($name) > 0)
            $userQuery->where('name','like','%'.$name.'%');
        if (strlen($keyword) > 0)
            $userQuery->where('keyword','like','%'.$keyword.'%');
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
        $validUserFields = $request->validated();
        $validUserFields['keyword'] = User::joinKeywords($request);

        $user = User::create($validUserFields);

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
        $validUserFields = $request->validated();
        $validUserFields['keyword'] = User::joinKeywords($request);

        $user->update($validUserFields);

        return Res::success(['id' => $user->id],'Updated successfully');
    }

    public function updatePassword(Request $request)
    {
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

        if ($user->avatar)
            $user->avatar->delete();

        $user->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }

    public function avatarupload(Request $request){

        $userData = User::findOrFail($request->user_id);

        if (!User::checkPermission($userData->role_id,'update'))
            return Res::error('Permission not allowed');

        if (!$request->file)
            return Res::error('File not set');

        $currentAvatar =  $userData
            ->addMedia(Helpers::getTempFileUrl($request->input('file')))
            ->toMediaCollection('avatar');

//            $userData
//                ->addFromMediaLibraryRequest($request->avatar)
//                ->toMediaCollection('avatar');

        return Res::success([
            'id' => $currentAvatar->id,
            'medium' => $currentAvatar->getUrl('medium'),
            'url' => $currentAvatar->original_url,
        ],'Updated successfully');
    }

    public function avatardelete(Request $request){

        $userData = User::findOrFail($request->user_id);

        if (!User::checkPermission($userData->role_id,'update'))
            return Res::error('Permission not allowed');

        $userData->avatar->delete();

        return Res::success(File::noImgRes('user'),'Updated successfully');
    }
}
