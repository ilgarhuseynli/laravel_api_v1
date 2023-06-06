<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Helpers;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use DateTime;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with('role')->withTrashed()->get();

        return view('admin.users.index', compact('users'));
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
            $userQuery->whereIn('role_id',$types);
        }

        $users = $userQuery->skip($skip)->take($limit)->get();
        $usersCount = $userQuery->count();

        $usersRes = [];
        foreach ($users as $user){
            $usersRes[] = [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'remote_tech' => $user->remote_tech,
            ];
        }

        return Res::custom([
            'status'=>'success',
            'data'=>$users,
            'count'=>$usersCount,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $technicians = User::where('role_id',Role::ROLE_TECH)->pluck('name', 'id');

        return view('admin.users.create', compact('roles', 'technicians'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->all();

        $senior = array_key_exists("senior_technician", $validated);

        if ($senior && array_key_exists("users_of_senior", $validated)){
            $integerUserIds = [];
            if (count((array)$validated['users_of_senior']) > 0){
                $integerUserIds = array_map(function ($id){ return (int)$id; }, (array)$validated['users_of_senior']);
            }
            $validated['users_of_senior'] = $integerUserIds;
        }else{
            $validated['users_of_senior'] = null;
        }

        if ($senior){
            $validated["senior_tech"] = (bool)$validated["senior_technician"];
        }

        if (!$validated["senior_tech"]){
            $validated['users_of_senior'] = null;

        }

        User::create($validated);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $technicians = User::where('role_id',Role::ROLE_TECH)->pluck('name', 'id');

        return view('admin.users.edit', compact('roles', 'user', 'technicians'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if($request->change_password != 1){
            $request->offsetUnset('password');
        }

        if ( $request->remote_tech == 1 && $request->role_id != Role::ROLE_TECH){
            return back()->withErrors('Cant make "non technic" remote-tech');
        }

        $validated = $request->all();

        if (!$request->deleted_at) {
            $date = new DateTime();
            $validated['deleted_at'] = $date->format('Y-m-d H:i:s');
        }else{
            $validated['deleted_at'] = null;
        }

        $senior = array_key_exists("senior_technician", $validated);

        if ($senior && array_key_exists("users_of_senior", $validated)){
            $integerUserIds = [];
            if (count((array)$validated['users_of_senior']) > 0){
                $integerUserIds = array_map(function ($id){ return (int)$id; }, (array)$validated['users_of_senior']);
            }
            $validated['users_of_senior'] = $integerUserIds;
        }else{
            $validated["users_of_senior"] = null;
        }

        if ($senior && (bool)$validated["senior_technician"]){
            $validated["senior_tech"] = true;
        }else{
            $validated["senior_tech"] = false;
            $validated['users_of_senior'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($user->senior_tech && $user->users_of_senior && count($user->users_of_senior) > 0) {
            $user->users_of_senior = User::withTrashed()->whereIn("id", $user->users_of_senior)->get();
        }

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
