<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class UploadedFile extends Model
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

        /*static::creating(function ($research) {
            $research->sorting_order = static::max('sorting_order')+1;
        });*/

        /*static::updating(function ($research) {
            if($research->getOriginal('department_id') != $research->department_id)
                $research->sorting_order = static::whereDepartmentId($research->department_id)->max('sorting_order')+1;
        });*/

        static::deleting(function ($file){
            if($file->path) Storage::delete("public/image/global/$file->path");
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $fromDate
     * @param bool $toDate
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $fromDate = false, $toDate = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $file = (new UploadedFile())->newQuery();

        if($fromDate && $toDate) $file = $file->where('created_on', '>=',  $fromDate)->where('created_on', '<=', $toDate);

        if($search) $file = $file->where("name", "regexp", "$search");

        if($relation) $file = $file->with($relation);

        return $file->paginate($paginate);
    }
}
