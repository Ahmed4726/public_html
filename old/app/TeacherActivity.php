<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeacherActivity extends Model
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

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($activity) {
            $maxAvailableSortNumber = static::whereTeacherId($activity->teacher_id)->max('sorting_order');
            if($maxAvailableSortNumber != '9999') $activity->sorting_order = $maxAvailableSortNumber+1;
        });*/

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $teacherID
     * @param bool $status
     * @param bool $orderBy
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $teacherID = false, $status = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $activity = (new TeacherActivity())->newQuery();

        if($teacherID) $activity = $activity->whereTeacherId($teacherID);

        if($status == 'inactive') $activity = $activity->whereStatus(0);
        elseif($status != false && $status) $activity = $activity->whereStatus($status);

        if($search) $activity = $activity->where(function ($query) use ($search){
            $query->orWhere("organization", "regexp", "$search")
                ->orWhere("position", "regexp", "$search")
                ->orWhere("period", "regexp", "$search");
        });

        if($relation) $activity = $activity->with($relation);

        return $activity->paginate($paginate);
    }
}
