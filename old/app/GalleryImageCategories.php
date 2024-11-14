<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryImageCategories extends Model
{
    /**
     * @var string
     */
    protected $table = 'gallery_image_categories';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
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

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }
}
