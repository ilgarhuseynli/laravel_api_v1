<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Category extends Model implements HasMedia
{
    use  InteractsWithMedia;

    public $table = 'categories';

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
        'status',
        'type',
        'created_at',
        'updated_at'
    ];

    const STATUS_SELECT = [
        1 => 'Active',
        0 => 'Deactive',
    ];

    const TYPE_SELECT = [
        'product',
        'service',
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
        return $query->where('status',1);
    }


    //WHEN SET ATTRIBUTE FUNCTIONS

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }



}
