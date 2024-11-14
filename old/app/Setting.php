<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'home_first_section_event' => 'array',
        'home_second_section_event' => 'array',
        'home_third_section_event' => 'array',
        'department_event' => 'array',
    ];

    /**
     * @param $key
     * @return mixed
     */
    public static function getDataByKey($key)
    {
        return static::first()->$key;
    }
}
