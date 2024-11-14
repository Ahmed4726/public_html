<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Center extends Model
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
    public function typeInfo()
    {
        return $this->belongsTo('App\CenterType', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function officers()
    {
        return $this->hasMany(Officer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\CenterFile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programs()
    {
        return $this->hasMany(DepartmentProgram::class);
    }

    public static function getDetailsBySlug($slug)
    {
        return static::with([
            'teacher', 'teacher.department', 'officers',
            'files' => function ($query) {
                $query->whereListingEnabled(1);
            },
            'programs' => function ($query) {
                $query->whereEnabled(1);
            }
        ])->whereSlug($slug)->first();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($center) {
            /*$maxAvailableSortNumber = static::whereTypeId($center->type_id)->max('sorting_order');
            $center->sorting_order = $maxAvailableSortNumber+1;*/
        });

        static::updating(function ($center) {
            /*if($center->getOriginal('type_id') != $center->type_id){
                $maxAvailableSortNumber = static::whereTypeId($center->type_id)->max('sorting_order');
                $center->sorting_order = $maxAvailableSortNumber+1;
            }*/
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::center::list') {
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('CENTER')) {
                    $userCenter = UserCenter::whereUserId(Auth::user()->id)->get(['center_id'])->pluck(['center_id']);
                    $builder->whereIn('id', $userCenter);
                }
            }

            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $typeID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter($search = false, $typeID = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $center = (new Center())->newQuery();

        if ($typeID) {
            $center = $center->whereTypeId($typeID);
        }

        if ($search) {
            $center = $center->where('name', 'regexp', "$search");
        }

        if ($relation) {
            $center = $center->with($relation);
        }

        return $center->paginate($paginate);
    }
}
