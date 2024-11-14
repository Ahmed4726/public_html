<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
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

    public function subMenu()
    {
        
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($research) {
            if($research->root_id)
                $research->sorting_order = static::whereRootId($research->root_id)->whereType($research->type)->max('sorting_order')+1;
            else
                $research->sorting_order = static::whereType($research->type)->max('sorting_order')+1;
        });*/

        /*static::updating(function ($research) {
            if($research->getOriginal('department_id') != $research->department_id)
                $research->sorting_order = static::whereDepartmentId($research->department_id)->max('sorting_order')+1;
        });*/

        static::deleting(function ($menu) {
            if($menu->type = 'MENU') static::whereType('SUB_MENU')->whereRootId($menu->id)->delete();
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $type
     * @param bool $status
     * @param bool $rootID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $type = false, $status = false, $rootID = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $menu = (new Menu())->newQuery();

        if($status == 'disable') $menu = $menu->whereEnabled(0);
        elseif($status != false && $status) $menu = $menu->whereEnabled($status);

        if($type) $menu = $menu->whereType($type);

        if($rootID) $menu = $menu->whereRootId($rootID);

        if($search) $menu = $menu->where("display_text", "regexp", "$search");

        if($relation) $menu = $menu->with($relation);

        return $menu->paginate($paginate);
    }
}
