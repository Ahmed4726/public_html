<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class DepartmentFile extends Model
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

        /*static::creating(function ($file) {
            $maxAvailableSortNumber = static::whereDepartmentId($file->department_id)->max('sorting_order');
            $file->sorting_order = $maxAvailableSortNumber+1;
        });*/

        static::deleting(function ($file){
            if($file->path) {
                $department = Department::find($file->department_id);
                Storage::delete("public/image/department/$department->id/$file->path");
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $status
     * @param bool $departmentID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $status = false, $departmentID = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $file = (new DepartmentFile())->newQuery();

        if($departmentID) $file = $file->whereDepartmentId($departmentID);

        if($status) $file = $file->whereListingEnabled($status);

        if($search) $file = $file->where("name", "regexp", "$search");

        if($relation) $file = $file->with($relation);

        return $file->paginate($paginate);
    }
}
