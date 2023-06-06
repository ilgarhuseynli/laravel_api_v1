<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Modules\Service\Entities\ServiceCategory;
use Modules\Service\Http\Requests\StoreServiceCategoryRequest;
use Modules\Service\Http\Requests\UpdateServiceCategoryRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;


class ServiceCategoriesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('service_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceCategories = ServiceCategory::all();

        return view('service::service-categories.index',compact('serviceCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('service_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('service::service-categories.create');
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        $serviceCategory = ServiceCategory::create($request->all());

        if ($request->input('image', false)) {
            $serviceCategory->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        return \response()->json(['status'=>'success','title'=>__('global.created'),'description'=>__('global.created_description',['attribute' => __('cruds.serviceCategory.title_singular')])]);
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        abort_if(Gate::denies('service_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('service::service-categories.edit',compact( 'serviceCategory'));
    }

    public function update(UpdateServiceCategoryRequest $request,ServiceCategory $serviceCategory)
    {
        $serviceCategory->update($request->all());

        if ($request->input('image', false)) {
            if (!$serviceCategory->image || $request->input('image') !== $serviceCategory->image->file_name) {
                $serviceCategory->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
            }
        } elseif ($serviceCategory->image) {
            $serviceCategory->image->delete();
        }

        return \response()->json(['status'=>'success','title'=>__('global.updated'),'description'=>__('global.updated_description',['attribute' => __('cruds.serviceCategory.title_singular')])]);
    }


    public function show(ServiceCategory $serviceCategory)
    {
        abort_if(Gate::denies('service_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('service::service-categories.show', compact('service'));
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        abort_if(Gate::denies('service_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceCategory->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('service_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:service_categories,id',
        ]);

        ServiceCategory::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('service_category_create') && Gate::denies('service_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ServiceCategory();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }


}
