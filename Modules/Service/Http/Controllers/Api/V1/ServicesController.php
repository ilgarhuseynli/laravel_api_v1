<?php

namespace Modules\Service\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\MediaUploadingTrait;
use Modules\Service\Entities\ServiceCategory;
use Modules\Service\Entities\ServicePrice;
use Modules\Service\Http\Requests\MassDestroyServiceRequest;
use Modules\Service\Http\Requests\StoreServiceRequest;
use Modules\Service\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;
use Modules\Service\Entities\Service;
use Symfony\Component\HttpFoundation\Response;
use Gate;


class ServicesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::all();

        return view('service::services.index',compact('services'));
    }

    public function create()
    {
        abort_if(Gate::denies('service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $categories = ServiceCategory::active()->pluck('name','id')->prepend(trans('global.pleaseSelect'), '');

        return view('service::services.create');
    }

    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->all());

        // prices add
        $this->servicePriceAdd($service->id,$request->service_prices);

//        if ($request->input('image', false)) {
//            $service->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
//        }

        return \response()->json(['status'=>'success','title'=>__('global.created'),'description'=>__('global.created_description',['attribute' => __('cruds.service.title_singular')])]);
    }

    public function edit(Service $service)
    {
        abort_if(Gate::denies('service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $categories = ServiceCategory::active()->pluck('name','id')->prepend(trans('global.pleaseSelect'), '');

        $service->load('prices','category');

        return view('service::services.edit',compact( 'service'));
    }

    public function update(UpdateServiceRequest $request,Service $service)
    {
        $service->update($request->all());

        //prices delete
        $service->prices()->delete();
        // prices add
        $this->servicePriceAdd($service->id,$request->service_prices);


//        if ($request->input('image', false)) {
//            if (!$service->image || $request->input('image') !== $service->image->file_name) {
//                $service->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
//            }
//        } elseif ($service->image) {
//            $service->image->delete();
//        }

        return \response()->json(['status'=>'success','title'=>__('global.updated'),'description'=>__('global.updated_description',['attribute' => __('cruds.service.title_singular')])]);
    }


    public function show(Service $service)
    {
        abort_if(Gate::denies('service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('service::services.show', compact('service'));
    }

    public function destroy(Service $service)
    {
        abort_if(Gate::denies('service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $service->delete();

        return back();
    }

    public function massDestroy(MassDestroyServiceRequest $request)
    {
        abort_if(Gate::denies('service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('service_create') && Gate::denies('service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Service();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function servicePriceAdd($service_id,$service_prices){
        if(count($service_prices['service_name']) > 0 && $service_prices['service_name'][0] != null){
            for ($i=0;$i<count($service_prices['service_name']);$i++){
                if($service_prices['service_name'][$i] != null && $service_prices['price'][$i] != null && $service_prices['price_type'][$i] != null && in_array($service_prices['price_type'][$i],array_keys(config('panel.price_types')))){
                    ServicePrice::create([
                        'service_id' => $service_id,
                        'service_name' => $service_prices['service_name'][$i],
                        'price'=>$service_prices['price'][$i],
                        'price_type'=>$service_prices['price_type'][$i]
                    ]);
                }
            }
        }
    }


}
