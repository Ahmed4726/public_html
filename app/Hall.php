<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Hall extends Model
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

    /**
     * @return mixed
     */
    private static function getPaginationNumber ()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @return string
     */
    public function getRealImagePathAttribute()
    {
        return ($this->image_url) ? asset("storage/image/hall/$this->image_url") : asset('images/default-hall-image.png');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($hall) {
            $maxAvailableSortNumber = static::max('sorting_order');
            $hall->sorting_order = $maxAvailableSortNumber+1;
        });*/

        static::deleting(function ($hall) {
            if($hall->image_url) \Storage::delete("public/image/hall/$hall->image_url");
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::hall::list'){
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('HALL')){
                    $userHall = UserHall::whereUserId(Auth::user()->id)->get(['hall_id'])->pluck(['hall_id']);
                    $builder->whereIn('id', $userHall);
                }
            }

            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $hall = (new Hall())->newQuery();

        if($search) $hall = $hall->where("name", "regexp", "$search");

        if($relation) $hall = $hall->with($relation);

        return $hall->paginate($paginate);
    }
}
