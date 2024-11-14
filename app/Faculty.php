<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Faculty extends Model
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
    public function dean()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacher()
    {
        return $this->hasMany('App\Teacher');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journal()
    {
        return $this->hasMany(Journal::class);
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($faculty) {
            $maxAvailableSortNumber = static::max('sorting_order');
            $faculty->sorting_order = $maxAvailableSortNumber+1;
        });*/

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::faculty::list'){
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('DEAN')){
                    $userFaculty= UserFaculty::whereUserId(Auth::user()->id)->get(['faculty_id'])->pluck(['faculty_id']);
                    $builder->whereIn('id', $userFaculty);
                }
            }
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param $slug
     * @return mixed
     */
    public static function getBySlug($slug)
    {
        return static::with(['dean', 'journal', 'departments'])->whereSlug($slug)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }


    /**
     * @param bool $search
     * @param bool $type
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $type = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $faculty = (new Faculty())->newQuery();

        if($type) $faculty = $faculty->whereType($type);

        if($search) $faculty = $faculty->where("name", "regexp", "$search");

        if($relation) $faculty = $faculty->with($relation);

        return $faculty->paginate($paginate);
    }
}
