<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class GalleryImages extends Model
{
    /**
     * @var string
     */
    protected $table = 'gallery_images';

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
     * @var array
     */
    protected $appends = ['real_image_path'];

    /**
     * @return bool|string
     */
    public function getRealImagePathAttribute()
    {
        return ($this->path) ? asset("storage/image/gallery/$this->path") : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function galleryImageCategories()
    {
        return $this->belongsTo('App\GalleryImageCategories', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @param bool $name
     * @return GalleryImages[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function imageByCategory($name = false){
        if($name){
            return self::whereHas('gelleryImageCategories', function ($query) use ($name){
                $query->where('name', $name);
            })->orderBy('sorting_order')->get();
        }

        return self::all();
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($image) {
            $image->sorting_order = static::whereCategoryId($image->category_id)->max('sorting_order')+1;
        });

        static::updating(function ($image) {
            if($image->getOriginal('category_id') != $image->category_id)
                $image->sorting_order = static::whereCategoryId($image->category_id)->max('sorting_order')+1;
        });*/

        static::deleting(function ($image){
            if($image->path) Storage::delete("public/image/gallery/$image->path");
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }


    /**
     * @param bool $search
     * @param bool $categoryId
     * @param bool $status
     * @param bool $departmentID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $categoryId = false, $status = false, $departmentID = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $image = (new GalleryImages())->newQuery();

        if($categoryId) $image = $image->whereCategoryId($categoryId);

        if($status == 'disable') $image = $image->whereEnabled(0);
        elseif($status != false && $status) $image = $image->whereEnabled($status);

        if(!$departmentID && !$categoryId) {
            $image = $image->whereHas('galleryImageCategories', function ($query) {
                $query->whereNull('department_id');
            });
        }
        else if($departmentID) {
            $image = $image->whereHas('galleryImageCategories', function ($query) use ($departmentID){
                $query->whereDepartmentId($departmentID);
            });;
        }

        if($search) $image = $image->where("title", "regexp", "$search");

        if($relation) $image = $image->with($relation);

        return $image->paginate($paginate);
    }
}
