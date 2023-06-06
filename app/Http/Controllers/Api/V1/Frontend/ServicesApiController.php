<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Service\ServiceInfoResource;
use App\Http\Resources\Service\ServiceListResource;
use Modules\Service\Entities\Service;

class ServicesApiController extends Controller
{

    public function list()
    {
        $services = Service::with('media')->active()->orderBy('id','desc')->get();

        return ServiceListResource::collection($services);
    }

    public function info($slug)
    {
        $service = Service::where('slug', $slug)->active()->first();

        return new ServiceInfoResource($service);
    }

}
