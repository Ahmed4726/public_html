<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Facility extends Model
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
    private static function getPaginationNumber()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function center()
    {
        return $this->belongsTo('App\Center');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($facility) {
            $query = 0;

            if(!$facility->department_id && !$facility->center_id)
                $query = static::whereNull('department_id')->whereNull('center_id')->max('sorting_order');
            if($facility->department_id)
                $query= static::whereDepartmentId($facility->department_id)->max('sorting_order');
            elseif ($facility->center_id)
                $query = static::whereCenterId($facility->center_id)->max('sorting_order')+1;

            $facility->sorting_order = $query+1;
        });*/

        /*static::updating(function ($facility) {
            if(!$facility->department_id && !$facility->center_id && ($facility->getOriginal('center_id') || $facility->getOriginal('department_id')))
                $facility->sorting_order = static::where('id', '!=', $facility->id)->whereNull('department_id')->whereNull('center_id')->max('sorting_order')+1;
            elseif($facility->department_id && $facility->getOriginal('department_id') != $facility->department_id)
                $facility->sorting_order = static::whereDepartmentId($facility->department_id)->max('sorting_order')+1;
            elseif ($facility->center_id && $facility->getOriginal('center_id') != $facility->center_id)
                $facility->sorting_order = static::whereCenterId($facility->center_id)->max('sorting_order')+1;

        });*/

        static::deleting(function ($facility) {
            if ($facility->image_url) {
                Storage::delete("public/image/facility/$facility->image_url");
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $departmentID
     * @param bool $centerID
     * @param bool $for
     * @param bool $status
     * @param bool $paginate
     * @param bool $relation
     * @return mixed
     */
    public static function allWithOptionalFilter($search = false, $departmentID = false, $centerID = false, $for = false, $status = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $facility = (new Facility())->newQuery();

        if ($for == 'global' || (!$departmentID && !$centerID)) {
            $facility = $facility->whereNull('department_id')->whereNull('center_id');
        }

        if ($departmentID) {
            $facility = $facility->whereDepartmentId($departmentID);
        }

        if ($centerID) {
            $facility = $facility->whereCenterId($centerID);
        }

        if ($status == 'disable') {
            $facility = $facility->whereEnabled(0);
        } elseif ($status != false && $status) {
            $facility = $facility->whereEnabled($status);
        }

        if ($search) {
            $facility = $facility->where('name', 'regexp', "$search");
        }

        if ($relation) {
            $facility = $facility->with($relation);
        }

        return $facility->paginate($paginate);
    }
}
