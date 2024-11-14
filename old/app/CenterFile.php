<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class CenterFile extends Model
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
    public function center()
    {
        return $this->belongsTo('App\Center');
    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedOnAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($file) {
            $maxAvailableSortNumber = static::whereCenterId($file->center_id)->max('sorting_order');
            $file->sorting_order = $maxAvailableSortNumber+1;
        });*/

        static::deleting(function ($file) {
            if($file->path) {
                $center = Center::find($file->center_id);
                Storage::delete("public/image/center/$center->id/$file->path");
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $status
     * @param bool $centerID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $status = false, $centerID = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $file = (new CenterFile())->newQuery();

        if($centerID) $file = $file->whereCenterId($centerID);

        if($status) $file = $file->whereListingEnabled($status);

        if($search) $file = $file->where("name", "regexp", "$search");

        if($relation) $file = $file->with($relation);

        return $file->paginate($paginate);
    }
}
