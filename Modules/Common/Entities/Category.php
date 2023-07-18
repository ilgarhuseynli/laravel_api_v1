<?php

namespace Modules\Common\Entities;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use  InteractsWithMedia;

    public $table = 'categories';

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        'title',
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


    //SCOPE FUNCTIONS
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }


    //WHEN SET ATTRIBUTE FUNCTIONS
    public function setNameAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

}
