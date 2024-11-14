<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Department extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /*
     * @var array
     */
    protected $casts = [
        'config' => 'array'
    ];

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
    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chairman()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function journal()
    {
        return $this->hasOne(Journal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function officers()
    {
        return $this->hasMany(Officer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacher()
    {
        return $this->hasMany('App\Teacher');
    }

    /**
     * @param array $status
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany[]
     */
    public function facultyMembersByStatus(array $status)
    {
        return $this->teacher()->whereIn('status', $status)->get();
    }

    /**
     * @param $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($department) {
            $maxAvailableSortNumber = static::whereFacultyId($department->faculty_id)->max('sorting_order');
            $department->sorting_order = $maxAvailableSortNumber+1;
        });*/

        /*static::updating(function ($department) {
            if($department->getOriginal('faculty_id') != $department->faculty_id){
                $maxAvailableSortNumber = static::where('id', '!=', $department->id)->whereFacultyId($department->faculty_id)->max('sorting_order');
                $department->sorting_order = $maxAvailableSortNumber+1;
            }
        });*/

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::department::list') {
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('DEPARTMENT')) {
                    $userDepartment = UserDepartment::whereUserId(Auth::user()->id)->get(['department_id'])->pluck(['department_id']);
                    $builder->whereIn('id', $userDepartment);
                }
            }
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    public static function getDetailsBySlug($slug)
    {
        return static::with([
            'chairman', 'faculty', 'journal', 'officers',
            'programs' => function ($query) {
                $query->whereTypeId(1)->whereEnabled(1);
            },
            'researches' => function ($query) {
                $query->whereEnabled(1);
            },
            'teacher' => function ($query) {
                $query->whereIn('status', [1, 2, 4])->take(5);
            },
            'teacher.designationInfo',
            'facilities' => function ($query) {
                $query->whereEnabled(1);
            },
            'files' => function ($query) {
                $query->whereListingEnabled(1);
            },
            'links' => function ($query) {
                $query->whereEnabled(1);
            }
        ])->whereSlug($slug)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(DepartmentFile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(DiscussionTopic::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programs()
    {
        return $this->hasMany(DepartmentProgram::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researches()
    {
        return $this->hasMany(Research::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    /**
     * @param bool $search
     * @param bool $facultyID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter($search = false, $facultyID = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $department = (new Department())->newQuery();

        if ($facultyID) {
            $department = $department->whereFacultyId($facultyID);
        }

        if ($search) {
            $department = $department->where(function ($query) use ($search) {
                $query->orWhere('name', 'regexp', "$search")
                ->orWhere('short_name', 'regexp', "$search");
            });
        }

        if ($relation) {
            $department = $department->with($relation);
        }

        return $department->paginate($paginate);
    }
}
