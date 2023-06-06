<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class ServicePriceCard extends Model
{
    use SoftDeletes;

    public $table = 'service_price_cards';


    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'starting_price',
        'category_id',
        'rank',
        'active',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    const ACTIVE_SELECT = [
        1 => 'Active',
        0 => 'Deactive',
    ];

    //RELATIONSHIPS

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id')->with('media');
    }


    //SCOPE FUNCTIONS

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }


    //WHEN SET ATTRIBUTE FUNCTIONS

    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    //EXTRA FUNCTIONS

    public function status()
    {
        return $this->attributes['active'] == 1 ? '<i class="fa fa-check-circle text-success fa-lg"></i>':'<i class="fa fa-times-circle text-danger fa-lg"></i>';
    }

}
