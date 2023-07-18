<?php

namespace Modules\Product\Http\Controllers\Api\V1;

use App\Classes\Helpers;
use App\Classes\Permission;
use App\Classes\Res;
use App\Http\Controllers\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        abort_if(!Permission::check('product_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $title = $request->title;
        $sku = $request->sku;
        $category = $request->category;
        $status = $request->status;

        $skip = (int)$request->skip;
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,Product::$sortable);


        $productQuery = Product::with('media','category');

        if (strlen($sku) > 0)
            $productQuery->where('sku','like','%'.$sku.'%');
        if (strlen($title) > 0)
            $productQuery->where('title','like','%'.$title.'%');
        if ($category)
            $productQuery->where('category_id',$category);
        if ($status)
            $productQuery->where('status',$status == 0 ? 0 : 1);

        $productsCount = $productQuery->count();
        $products = $productQuery->orderBy($sort[0],$sort[1])->skip($skip)->take($limit)->get();

        return Res::custom([
            'status'=>'success',
            'data'=> ProductResource::collection($products),
            'count'=>$productsCount,
            'skip'=>$skip,
            'limit'=>$limit,
        ]);
    }


    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        if ($request->input('image', false)) {
            $product
                ->addMedia(Helpers::getTempFileUrl($request->input('file')))
                ->toMediaCollection('image');
        }

        return Res::success(['id' => $product->id],'Created successfully');
    }

    public function update(UpdateProductRequest $request,Product $product)
    {
        $product->update($request->validated());

        return Res::success(['id' => $product->id],'Updated successfully');
    }


    public function show(Product $product)
    {
        abort_if(!Permission::check('product_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Res::success(new ProductResource($product));
    }

    public function destroy(Product $product)
    {
        abort_if(!Permission::check('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($product->image)
            $product->image->delete();

        $product->delete();

        return Res::success([], "Deleted", "Successfully deleted");
    }


    public function fileupload(Request $request){
        abort_if(!Permission::check('product_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productData = Product::findOrFail($request->product);

        if ($productData->image)
            $productData->image->delete();

        $validated = $request->validate([
            'file' => ['required'],
        ]);

        $currentFile = $productData
            ->addMedia(Helpers::getTempFileUrl($request->input('file')))
            ->toMediaCollection('image');

        return Res::success([
            'id' => $currentFile->id,
            'medium' => $currentFile->getUrl('medium'),
            'url' => $currentFile->url,
        ],'Updated successfully');
    }

    public function filedelete(Request $request){
        abort_if(!Permission::check('product_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productData = Product::findOrFail($request->product);

        $productData->image->delete();

        return Res::success(['id' => $productData->id],'Updated successfully');
    }

}
