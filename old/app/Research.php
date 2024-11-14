<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $table = 'researches';

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
    public function typeInfo()
    {
        return $this->belongsTo('App\ResearchType', 'type_id');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($research) {
            $research->sorting_order = static::max('sorting_order')+1;
        });*/

        /*static::updating(function ($research) {
            if($research->getOriginal('department_id') != $research->department_id)
                $research->sorting_order = static::whereDepartmentId($research->department_id)->max('sorting_order')+1;
        });*/

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
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
    public static function allWithOptionalFilter($search = false, $departmentID = false, $typeID = false, $status = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $research = (new Research())->newQuery();

        if ($departmentID) {
            $research = $research->whereDepartmentId($departmentID);
        }

        if ($typeID) {
            $research = $research->whereTypeId($typeID);
        }

        if ($status == 'disable') {
            $research = $research->whereEnabled(0);
        } elseif ($status != false && $status) {
            $research = $research->whereEnabled($status);
        }

        if ($search) {
            $research = $research->where('name', 'regexp', "$search");
        }

        if ($relation) {
            $research = $research->with($relation);
        }

        return $research->paginate($paginate);
    }
}
