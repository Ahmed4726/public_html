<?php

namespace App\Traits;

use App\Setting;
use Helper;

trait ExtendAble
{
    /**
     * Override Pagination Number
     *
     * @return void
     */
    public function getPerPage()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * Get My Model All Status
     *
     * @return object
     */
    public static function allStatus()
    {
        return Helper::allStatus(self::class);
    }

    /**
     * Get My Model Only Specific Status
     *
     * @param [type] $status
     * @return object
     */
    public static function onlyStatus($status)
    {
        return Helper::allStatus(self::class, $status);
    }
}
