<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Service extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'services';

    public static $searchable = [
        'slug',
        'title',
        'content',
    ];

//    protected $appends = [
//        'image',
//    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'position',
        'content',
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

    const POSITION_SELECT = [
        0 => 'Panel',
        1 => 'Home page',
    ];

    const SERVICE_ICONS = [
        0 => 'Refrigerator',
        1 => 'Ice Machine',
        2 => 'Washer',
        3 => 'Dryer',
        4 => 'Dishwasher',
        5 => 'Oven',
        6 => 'Cooktop',
        7 => 'Microwave',
        8 => 'Wine Cooler',
        9 => 'Freezer',
    ];

    //Media image config
//    public function getImageAttribute()
//    {
//        $file = $this->getMedia('image')->last();
//
//        if ($file) {
//            $file->url = $file->getUrl();
//            $file->thumbnail = $file->getUrl('thumb');
//            $file->medium = $file->getUrl('medium');
//            $file->large = $file->getUrl('large');
//        }
//        return $file;
//    }


    public function registerMediaConversions(Media $media = null): void
    {
//        $this
//            ->addMediaConversion('preview')
//            ->fit(Manipulations::FIT_CROP, 300, 300)
//            ->nonQueued();

        $this->addMediaConversion('thumb')->width(150)->height(150)->performOnCollections('image');

        $this->addMediaConversion('medium')->width(600)->height(600)->performOnCollections('image');

        $this->addMediaConversion('large')->width(1000)->height(1000);
    }


    //RELATIONSHIPS

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(ServicePrice::class, 'service_id');
    }


    //SCOPE FUNCTIONS

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }


    //WHEN SET ATTRIBUTE FUNCTIONS

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    //EXTRA FUNCTIONS

    public function status()
    {
        return $this->attributes['active'] == 1 ? '<i class="fa fa-check-circle text-success fa-lg"></i>' : '<i class="fa fa-times-circle text-danger fa-lg"></i>';
    }

    public function getIcon()
    {
        $icon = $this->attributes['icon'];
        return asset("images/ultrafix/svg/".str_replace(' ','-',strtolower(self::SERVICE_ICONS[$icon])).".svg");

//        if ($icon) {
//            return asset("images/ultrafix/svg/".str_replace(' ','-',strtolower(self::SERVICE_ICONS[$icon])).".svg");
//        } else {
//            return asset("images/no-img.png");
//        }
    }


//    public function getImage($imageType)
//    {
//        $image = $this->getImageAttribute();
//        if ($image) {
//            return $image->getUrl($imageType);
//        } else {
//            return asset("images/no-img.png");
//        }
//    }
}
