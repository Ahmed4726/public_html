<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    private static function getPaginationNumber ()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @param $slug
     * @param int $enabled
     * @return mixed
     */
    public static function getBySlug($slug, $enabled = 1)
    {
        return static::whereSlug($slug)->whereEnabled($enabled)->first();
    }

    /**
     * @param bool $search
     * @param bool $status
     * @param bool $orderBy
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $status = false, $orderBy = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        if(!$orderBy)
            $page = self::orderBy('id', 'desc');
        else
            $page = self::orderBy('id', $orderBy);

        if($status === '0') $page = $page->whereEnabled(0);
        elseif($status == false) $page = $page;
        else $research = $page->whereEnabled($status);

        if($search) $page = $page->where("title", "regexp", "$search");

        if($relation) $page = $page->with($relation);

        return $page->paginate($paginate);
    }
}
