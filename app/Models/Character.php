<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'species',
        'type',
        'gender',
        'origin_name',
        'origin_url',
        'location_name',
        'location_url',
        'image_url',
        'episode_urls',
        'url',
        'created_at_api',
    ];

    protected $casts = [
        'episode_urls' => 'array',
        'created_at_api' => 'datetime',
    ];
}
