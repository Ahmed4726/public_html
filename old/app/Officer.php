<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Officer extends Model
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
     * @var array
     */
    protected $appends = ['real_image_path'];


    public function getRealImagePathAttribute()
    {
        return ($this->image_url) ? asset("storage/image/officer/$this->image_url") : asset('images/default-img-person.jpg');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusInfo()
    {
        return $this->belongsTo('App\TeacherStatus', 'status');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeInfo()
    {
        return $this->belongsTo('App\OfficerType', 'type_id');
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
    public function center()
    {
        return $this->belongsTo('App\Center');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($officer) {
            $maxAvailableSortNumber = 0;

            if($officer->department_id)
                $maxAvailableSortNumber = static::whereDepartmentId($officer->department_id)->whereTypeId($officer->type_id)->max('sorting_order');
            elseif ($officer->center_id)
                $maxAvailableSortNumber = static::whereCenterId($officer->center_id)->whereTypeId($officer->type_id)->max('sorting_order');

            $officer->sorting_order = $maxAvailableSortNumber+1;
        });

        static::updating(function ($officer) {
            if($officer->department_id && $officer->getOriginal('department_id') != $officer->department_id)
                $officer->sorting_order = static::whereDepartmentId($officer->department_id)->whereTypeId($officer->type_id)->max('sorting_order')+1;
            elseif ($officer->center_id && $officer->getOriginal('center_id') != $officer->center_id)
                $officer->sorting_order = static::whereCenterId($officer->center_id)->whereTypeId($officer->type_id)->max('sorting_order')+1;
            elseif ($officer->getOriginal('department_id') == $officer->department_id && $officer->getOriginal('center_id') == $officer->center_id && $officer->getOriginal('type_id') != $officer->type_id)
                $officer->sorting_order = static::whereCenterId($officer->center_id)->whereDepartmentId($officer->department_id)->whereTypeId($officer->type_id)->max('sorting_order')+1;
        });*/

        static::deleting(function ($officer) {
            if($officer->image_url) Storage::delete("public/image/officer/$officer->image_url");
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $status
     * @param bool $departmentID
     * @param bool $centerID
     * @param bool $typeID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $status = false, $departmentID = false, $centerID = false, $typeID = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $officer = (new Officer())->newQuery();

        if($status == 'inactive') $officer = $officer->whereStatus(0);
        elseif($status && (is_numeric($status))) $officer = $officer->whereStatus($status);
        elseif($status && (is_array($status)) && !empty($status)) $officer = $officer->whereIn('status', $status);

        if($departmentID) $officer = $officer->whereDepartmentId($departmentID);

        if($centerID) $officer = $officer->whereCenterId($centerID);

        if($typeID) $officer = $officer->whereTypeId($typeID);

        if($search) $officer = $officer->where(function ($query) use ($search){
            $query->orWhere("name", "regexp", "$search")
                ->orWhere("designation", "regexp", "$search")
                ->orWhere("email", "regexp", "$search");
        });


        if($relation) $officer = $officer->with($relation);

        return $officer->paginate($paginate);
    }
}
