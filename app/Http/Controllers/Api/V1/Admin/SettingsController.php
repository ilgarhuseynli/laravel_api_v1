<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\Admin\SettingResource;
use App\Models\Setting;
use Hamcrest\Core\Set;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!Permission::check('setting_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = Setting::all();

        $settingsByKey = [];
        foreach ($settings as $setting){
            $settingsByKey[$setting->key] = $setting->value;
        }

        $res = [];
        foreach (Setting::ALLOWED_KEYS as $key){
            $res[$key] = $settingsByKey[$key] ?? '';
        }

        return Res::custom([
            'status'=>'success',
            'data'=> $res,
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key=>$value){
            if (in_array($key,Setting::ALLOWED_KEYS)){
                Setting::updateOrCreate([
                    'key' => $key,
                ],[
                    'value' => $value,
                ]);
            }
        }

        return Res::success([],'Updated successfully');
    }

}
