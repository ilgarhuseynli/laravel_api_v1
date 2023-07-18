<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(!Permission::check('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = Setting::orderBy('id','asc')->get();

        return view('admin.settings.index', compact('settings'));
    }

    public function create()
    {
        abort_if(!Permission::check('setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.settings.create');
    }

    public function store(StoreSettingRequest $request)
    {
        $setting = Setting::create($request->all());

        return redirect()->route('admin.settings.index');
    }

    public function edit(Setting $setting)
    {
        abort_if(!Permission::check('setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $setting->update(['value'=>$request->value]);

        return redirect()->route('admin.settings.index');
    }

    public function show(Setting $setting)
    {
        abort_if(!Permission::check('setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.settings.show', compact('setting'));
    }

    public function destroy(Setting $setting)
    {
        abort_if(!Permission::check('setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $setting->delete();

        return back();
    }

}
