<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    public $table = 'service_prices';

    public $timestamps = false;

    protected $fillable = [
        'service_name',
        'price',
        'price_type',
        'service_id',
    ];


    //RELATIONSHIPS

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }


    const PRICE_TYPE_SELECT = [
        0 => 'AZN',
        1 => 'USD',
        2 => 'EUR',
    ];
}
