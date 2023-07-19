<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
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
        'logo',
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

}
