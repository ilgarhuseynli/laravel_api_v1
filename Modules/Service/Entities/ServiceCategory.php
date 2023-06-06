<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class ServiceCategory extends Model implements HasMedia
{
    use  InteractsWithMedia;

    public $table = 'service_categories';

    protected $appends = [
        'image',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        'name',
        'slug',
        'active',
        'created_at',
        'updated_at'
    ];


    const ACTIVE_SELECT = [
        1 => 'Active',
        0 => 'Deactive',
    ];


    //Media image config
    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->medium    = $file->getUrl('medium');
        }
        return $file;
    }


    public function registerMediaConversions(Media $media = null):void
    {
        $this->addMediaConversion('thumb')->width(150)->height(150)->performOnCollections('image');
        $this->addMediaConversion('medium')->width(400)->height(400)->performOnCollections('image');
    }

    //SCOPE FUNCTIONS

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }


    //WHEN SET ATTRIBUTE FUNCTIONS

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    //EXTRA FUNCTIONS

    public function status()
    {
        return $this->attributes['active'] == 1 ? '<i class="fa fa-check-circle text-success fa-lg"></i>':'<i class="fa fa-times-circle text-danger fa-lg"></i>';
    }


    public function getImage($imageType=''){
        $image = $this->getImageAttribute();
        if($image){
            return $image->getUrl($imageType);
        }else{
            return asset("images/no-img.png");
        }
    }
}
