<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeacherExperience extends Model
{
    protected $table = 'teacher_experiences';
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

        /*static::creating(function ($experience) {
            $maxAvailableSortNumber = static::whereTeacherId($experience->teacher_id)->max('sorting_order');
            if($maxAvailableSortNumber != '9999') $experience->sorting_order = $maxAvailableSortNumber+1;
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

        $experience = (new TeacherExperience())->newQuery();

        if($teacherID) $experience = $experience->whereTeacherId($teacherID);

        if($status == 'inactive') $experience = $experience->whereStatus(0);
        elseif($status != false && $status) $experience = $experience->whereStatus($status);

        if($search) $experience = $experience->where(function ($query) use ($search){
            $query->orWhere("organization", "regexp", "$search")
                ->orWhere("position", "regexp", "$search")
                ->orWhere("period", "regexp", "$search");
        });

        if($relation) $experience = $experience->with($relation);

        return $experience->paginate($paginate);
    }
}
