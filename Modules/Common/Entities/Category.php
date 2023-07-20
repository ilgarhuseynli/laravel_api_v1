<?php

namespace Modules\Common\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
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
    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

}
