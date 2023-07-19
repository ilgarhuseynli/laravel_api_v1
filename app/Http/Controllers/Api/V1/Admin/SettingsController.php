<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\Admin\SettingResource;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!Permission::check('setting_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = Setting::all();

        return Res::custom([
            'status'=>'success',
            'data'=> SettingResource::collection($settings),
        ]);
    }

    public function update(UpdateSettingRequest $request)
    {
        foreach ($request->validated() as $key=>$value){

            Setting::updateOrCreate([
                'key' => $key,
            ],[
                'value' => $value,
            ]);
        }

        return Res::success([],'Updated successfully');
    }

}
