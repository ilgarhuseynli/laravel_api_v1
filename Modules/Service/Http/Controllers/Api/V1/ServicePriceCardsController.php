<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Service\Http\Requests\StoreServicePriceCardRequest;
use Modules\Service\Http\Requests\UpdateServicePriceCardRequest;
use Symfony\Component\HttpFoundation\Response;
use Modules\Service\Entities\ServiceCategory;
use Illuminate\Http\Request;
use Modules\Service\Entities\ServicePriceCard;
use Gate;


class ServicePriceCardsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('service_price_card_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $priceCards = ServicePriceCard::with('category')->get();

        return view('service::service-price-cards.index',compact('priceCards'));
    }

    public function create()
    {
        abort_if(Gate::denies('service_price_card_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ServiceCategory::active()->pluck('name','id');

        return view('service::service-price-cards.create',compact('categories'));
    }

    public function store(StoreServicePriceCardRequest $request)
    {
        ServicePriceCard::create($request->all());

        return \response()->json(['status'=>'success','title'=>__('global.created'),'description'=>__('global.created_description',['attribute' => __('cruds.servicePriceCard.title_singular')])]);
    }

    public function edit(ServicePriceCard $servicePriceCard)
    {
        abort_if(Gate::denies('service_price_card_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ServiceCategory::active()->pluck('name','id');

        return view('service::service-price-cards.edit',compact( 'servicePriceCard','categories'));
    }

    public function update(UpdateServicePriceCardRequest $request,ServicePriceCard $servicePriceCard)
    {
        $servicePriceCard->update($request->all());

        return \response()->json(['status'=>'success','title'=>__('global.updated'),'description'=>__('global.updated_description',['attribute' => __('cruds.servicePriceCard.title_singular')])]);
    }


    public function show(ServicePriceCard $servicePriceCard)
    {
        abort_if(Gate::denies('service_price_card_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('service::service-price-cards.show', compact('servicePriceCard'));
    }

    public function destroy(ServicePriceCard $servicePriceCard)
    {
        abort_if(Gate::denies('service_price_card_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servicePriceCard->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('service_price_card_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:service_price_cards,id',
        ]);

        ServicePriceCard::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
