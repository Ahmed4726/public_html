<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeacherTeaching extends Model
{
    //protected $table = 'teacher_educations';
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

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($education) {
            $maxAvailableSortNumber = static::whereTeacherId($education->teacher_id)->max('sorting_order');
            if($maxAvailableSortNumber != '9999') $education->sorting_order = $maxAvailableSortNumber+1;
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
    public static function allWithOptionalFilter($search = false, $teacherID = false, $status = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $education = (new TeacherTeaching())->newQuery();

        if ($teacherID) {
            $education = $education->whereTeacherId($teacherID);
        }

        if ($status == 'inactive') {
            $education = $education->whereStatus(0);
        } elseif ($status != false && $status) {
            $education = $education->whereStatus($status);
        }

        if ($search) {
            $education = $education->where(function ($query) use ($search) {
                $query->orWhere('course_code', 'regexp', "$search")
                ->orWhere('course_title', 'regexp', "$search")
                ->orWhere('semester', 'regexp', "$search");
            });
        }

        if ($relation) {
            $education = $education->with($relation);
        }

        return $education->paginate($paginate);
    }
}
