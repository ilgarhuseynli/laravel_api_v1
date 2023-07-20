<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $table = 'settings';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'key',
        'value',
        'created_at',
        'updated_at',
    ];


    const ALLOWED_KEYS = [
        'title',
        'description',
        'email',
        'address',
        'map_location',
        'short_number',
        'phone',
        'opening_hours',
        'reg_id',

        'social_wp',
        'social_fb',
        'social_telegram',
        'social_instagram',
        'social_linkedin',
        'social_twitter',
    ];



    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(150)->height(150);

        $this->addMediaConversion('medium')->width(600)->height(600);

        $this->addMediaConversion('large')->width(1000)->height(1000);
    }

}
