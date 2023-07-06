<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();

        return Res::success(RoleResource::collection($roles));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return Res::success(['id' => $role->id],'Created successfully');
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        dd('error');
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return Res::success(['id' => $role->id],'Updated successfully');
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return Res::success(new RoleResource($role));
    }

    public function destroy(Role $role)
    {
        dd('error');
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }
}
