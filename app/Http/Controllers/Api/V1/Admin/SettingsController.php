<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Classes\File;
use App\Classes\Helpers;
use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
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

        $logoData = $settingsByKey['logo'] ? Media::findByUuid($settingsByKey['logo']) : false;

        $res['logo'] = File::getFileObject($logoData);

        return Res::custom([
            'status'=>'success',
            'data'=> $res,
        ]);
    }

    public function update(Request $request)
    {
        abort_if(!Permission::check('setting_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reqData = $request->all();

        foreach ($reqData as $key=>$value){
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

    public function fileupload(Request $request){
        abort_if(!Permission::check('setting_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$request->file)
            return Res::error('File not set');

        $settingData = Setting::firstOrCreate(['key' => 'logo']);

        if ($settingData->value){
            $mediaFile =  Media::findByUuid($settingData->value);

            $mediaFile->delete();
        }

        $uploadedFile = $settingData
            ->addMedia(Helpers::getTempFileUrl($request->input('file')))
            ->toMediaCollection('logo');

        $settingData->update(['value'=>$uploadedFile->uuid]);

        return Res::success([
            'data' => $uploadedFile,
            'id' => $uploadedFile->id,
            'medium' => $uploadedFile->getUrl('medium'),
            'url' => $uploadedFile->original_url,
        ],'Updated successfully');
    }

    public function filedelete(){
        abort_if(!Permission::check('setting_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settingData = Setting::firstOrCreate(['key' => 'logo']);

        $mediaFile =  Media::findByUuid($settingData->value);

        $mediaFile->delete();

        $settingData->update(['value'=>'']);

        return Res::success(File::noImgRes(),'Updated successfully');
    }

}
