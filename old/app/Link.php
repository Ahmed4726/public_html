<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeInfo()
    {
        return $this->belongsTo('App\LinkType', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($link) {
            $query = (new Link())->newQuery()->whereTypeId($link->type_id);
            if($link->department_id) $query = $query->whereDepartmentId($link->department_id);
            $link->sorting_order = $query->max('sorting_order')+1;
        });

        static::updating(function ($link) {
            $query = (new Link())->newQuery()->whereTypeId($link->type_id);

            if($link->department_id && $link->getOriginal("department_id") != $link->department_id)
                $link->sorting_order = $query->whereDepartmentId($link->department_id)->max('sorting_order')+1;

            if(!$link->department_id && $link->getOriginal("department_id") != $link->department_id)
                $link->sorting_order = $query->whereNull('department_id')->max('sorting_order')+1;

            if($link->getOriginal("department_id") == $link->department_id && $link->getOriginal("type_id") != $link->type_id)
                $link->sorting_order = $query->max('sorting_order')+1;
        });*/

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('type_id', 'asc')->orderBy('sorting_order', 'asc');
        });
    }

    /**
     * @param bool $search
     * @param bool $departmentID
     * @param bool $typeID
     * @param bool $status
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $departmentID = false, $typeID = false, $status = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $link = (new Link())->newQuery();

        if($departmentID == 'global' || !$departmentID) $link = $link->whereNull('department_id');
        else if($departmentID) $link = $link->whereDepartmentId($departmentID);

        if($typeID) $link = $link->whereTypeId($typeID);

        if($status == 'disable') $link = $link->whereEnabled(0);
        elseif($status != false && $status) $link = $link->whereEnabled($status);

        if($search) $link = $link->where("label", "regexp", "$search");

        if($relation) $link = $link->with($relation);

        return $link->paginate($paginate);
    }
}
