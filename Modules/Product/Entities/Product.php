<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Common\Entities\Category;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'products';

    protected $appends = [
        'image',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'price',
        'description',
        'category_id',
        'position',
        'status',
        'sku',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    public static $sortable = [
        "id",
        "title",
        "price",
        'status',
        'sku',
        'created_at',
    ];


    const STATUS_SELECT = [
        1 => 'Active',
        0 => 'Deactive',
    ];

    const POSITION_SELECT = [
        1 => 'left',
        2 => 'center',
        3 => 'right',
    ];

    //Media image config
    public function getImageAttribute()
    {
        return $this->getMedia('image')->last();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(150)->height(150);

        $this->addMediaConversion('medium')->width(600)->height(600);

        $this->addMediaConversion('large')->width(1000)->height(1000);
    }


    //RELATIONSHIPS

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    //SCOPE FUNCTIONS

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    //WHEN SET ATTRIBUTE FUNCTIONS

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

}
