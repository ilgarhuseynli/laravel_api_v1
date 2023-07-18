<?php

namespace Modules\Common\Http\Controllers\Api\V1;

use App\Classes\Res;
use App\Classes\Helpers;
use App\Classes\Permission;
use App\Http\Controllers\Controller;
use Modules\Common\Entities\Category;
use Modules\Common\Http\Requests\StoreCategoryRequest;
use Modules\Common\Http\Requests\UpdateCategoryRequest;
use Modules\Common\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!Permission::check('category_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $title = $request->title;
        $type = $request->type;
        $status = $request->status;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type);

        $categoryQuery = Category::query();

        if (strlen($title) > 0)
            $categoryQuery->where('title','like','%'.$title.'%');
        if ($type)
            $categoryQuery->where('type',$type);
        if ($status)
            $categoryQuery->where('status',$status == 0 ? 0 : 1);

        $categorysCount = $categoryQuery->count();
        $categorys = $categoryQuery->orderBy($sort[0],$sort[1])->skip($skip)->take($limit)->get();

        return Res::custom([
            'status'=>'success',
            'data'=> CategoryResource::collection($categorys),
            'count'=>$categorysCount,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return Res::success(['id' => $category->id],'Created successfully');
    }

    public function update(UpdateCategoryRequest $request,Category $category)
    {
        $category->update($request->validated());

        return Res::success(['id' => $category->id],'Updated successfully');
    }

    public function show(Category $category)
    {
        abort_if(!Permission::check('category_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Res::success(new CategoryResource($category));
    }

    public function destroy(Category $category)
    {
        abort_if(!Permission::check('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }

}
