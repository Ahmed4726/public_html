<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Teacher extends Model
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
     * @var array
     */
    protected $appends = ['real_image_path'];

    public function getRealImagePathAttribute()
    {
        return ($this->image_url) ? asset("storage/image/teacher/$this->image_url") : asset('images/default-img-person.jpg');
    }

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
    public function designationInfo()
    {
        return $this->belongsTo('App\TeacherDesignation', 'designation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusInfo()
    {
        return $this->belongsTo('App\TeacherStatus', 'status');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function educations()
    {
        return $this->hasMany('App\TeacherEducation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teachings()
    {
        return $this->hasMany('App\TeacherTeaching');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany('App\TeacherActivity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experiences()
    {
        return $this->hasMany('App\TeacherExperience');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications()
    {
        return $this->hasMany('App\TeacherPublication');
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($teacher) {
            $maxAvailableSortNumber = static::whereDepartmentId($teacher->department_id)->max('sorting_order');
            if($maxAvailableSortNumber != '9999') $teacher->sorting_order = $maxAvailableSortNumber+1;
        });*/

        static::updating(function ($teacher) {
            if ($teacher->getOriginal('email') != $teacher->email) {
                static::updateUserRegadingTeacher($teacher->id, ['email' => $teacher->email]);
            }
            if ($teacher->getOriginal('name') != $teacher->name) {
                static::updateUserRegadingTeacher($teacher->id, ['name' => $teacher->name]);
            }
        });

        static::deleting(function ($teacher) {
            if ($teacher->image_url) {
                \Storage::delete("public/image/teacher/$teacher->image_url");
            }

            if ($userTeacher = UserTeacher::whereTeacherId($teacher->id)->first()) {
                UserTeacher::whereTeacherId($teacher->id)->delete();
                User::find($userTeacher->user_id)->delete();
            }

            foreach (TeacherActivity::whereTeacherId($teacher->id)->get() as $activity) {
                $activity->delete();
            }

            foreach (TeacherPublication::whereTeacherId($teacher->id)->get() as $publication) {
                $publication->delete();
            }

            foreach (TeacherTeaching::whereTeacherId($teacher->id)->get() as $teacheing) {
                $teacheing->delete();
            }

            foreach (TeacherExperience::whereTeacherId($teacher->id)->get() as $experience) {
                $experience->delete();
            }

            foreach (TeacherEducation::whereTeacherId($teacher->id)->get() as $education) {
                $education->delete();
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::teacher::list') {
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('TEACHER')) {
                    $userTeacher = UserTeacher::whereUserId(Auth::user()->id)->first(['teacher_id']);
                    ($userTeacher) ? $builder->whereId($userTeacher->teacher_id) : $builder->whereId($userTeacher);
                }
            }

            $builder->orderBy('sorting_order', 'asc');
        });
    }

    public static function updateUserRegadingTeacher($id, array $update)
    {
        $userTeacher = UserTeacher::whereTeacherId($id)->first();
        if ($userTeacher) {
            User::find($userTeacher->user_id)->update($update);
            return true;
        }

        return false;
    }

    /**
     * @param bool $search
     * @param bool $status
     * @param bool $department
     * @param bool $designation
     * @param array $orderBy
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter($search = false, $status = false, $department = false, $designation = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $teacher = (new Teacher())->newQuery();

        if ($department) {
            $teacher = $teacher->whereDepartmentId($department);
        } else {
            $teacher = $teacher->orderBy('designation_id', 'asc')->orderBy('name', 'asc');
        }

        if ($designation) {
            $teacher = $teacher->whereDesignationId($designation);
        }

        if ($status == 'inactive') {
            $teacher = $teacher->whereStatus(0);
        } elseif ($status && (is_numeric($status))) {
            $teacher = $teacher->whereStatus($status);
        } elseif ($status && (is_array($status)) && !empty($status)) {
            $teacher = $teacher->whereIn('status', $status);
        }

        if ($search) {
            $teacher = $teacher->where(function ($query) use ($search) {
                $query->orWhere('name', 'regexp', "$search")
                ->orWhere('email', 'regexp', "$search");
            });
        }

        if ($relation) {
            $teacher = $teacher->with($relation);
        }

        return $teacher->paginate($paginate);
    }
}
