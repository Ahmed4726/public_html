<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class DepartmentProgram extends Model
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
    private static function getPaginationNumber()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeInfo()
    {
        return $this->belongsTo('App\ProgramType', 'type_id');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hall()
    {
        return $this->belongsTo('App\Hall');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($program) {
            if(!$program->department_id && !$program->center_id && !$program->hall_id)
                $program->sorting_order = static::whereNull('department_id')->whereNull('center_id')->whereNull('hall_id')->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif($program->department_id)
                $program->sorting_order = static::whereDepartmentId($program->department_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif ($program->center_id)
                $program->sorting_order = static::whereCenterId($program->center_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif ($program->hall_id)
                $program->sorting_order = static::whereHallId($program->hall_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
        });

        static::updating(function ($program) {
            if(!$program->department_id && !$program->center_id && !$program->hall_id && ($program->getOriginal('center_id') || $program->getOriginal('department_id') || $program->getOriginal('hall_id')))
                $program->sorting_order = static::where('id', '!=', $program->id)->whereNull('department_id')->whereNull('center_id')->whereNull('hall_id')->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif($program->department_id && $program->getOriginal('department_id') != $program->department_id)
                $program->sorting_order = static::whereDepartmentId($program->department_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif ($program->center_id && $program->getOriginal('center_id') != $program->center_id)
                $program->sorting_order = static::whereCenterId($program->center_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif ($program->hall_id && $program->getOriginal('hall_id') != $program->hall_id)
                $program->sorting_order = static::whereHallId($program->hall_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
            elseif ($program->getOriginal('department_id') == $program->department_id && $program->getOriginal('center_id') == $program->center_id && $program->getOriginal('hall_id') == $program->hall_id && $program->getOriginal('type_id') != $program->type_id)
                $program->sorting_order = static::whereCenterId($program->center_id)->whereDepartmentId($program->department_id)->whereHallId($program->hall_id)->whereTypeId($program->type_id)->max('sorting_order')+1;
        });*/

        static::deleting(function ($program) {
            if ($program->path) {
                Storage::delete("public/image/program/$program->path");
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $departmentID
     * @param bool $centerID
     * @param bool $hallID
     * @param bool $for
     * @param bool $typeID
     * @param bool $paginate
     * @param bool $relation
     * @param array $options
     * @return mixed
     */
    public static function allWithOptionalFilter($search = false, $departmentID = false, $centerID = false, $hallID = false, $for = false, $typeID = false, $paginate = false, $relation = false, $options = [])
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $program = (new DepartmentProgram())->newQuery();

//        if($for == 'global') $program = $program->whereNull('department_id')->whereNull('center_id')->whereNull('hall_id');
        if ($for == 'global' || (!$departmentID && !$centerID && !$hallID)) {
            $program = $program->whereNull('department_id')->whereNull('center_id')->whereNull('hall_id');
        }

        if ($departmentID && !$for) {
            $program = $program->whereDepartmentId($departmentID);
        }

        if ($centerID && !$for) {
            $program = $program->whereCenterId($centerID);
        }

        if ($hallID && !$for) {
            $program = $program->whereHallId($hallID);
        }

        if ($typeID) {
            $program = $program->whereTypeId($typeID);
        }

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $program = $program->where($key, $value);
            }
        }

        if ($search) {
            $program = $program->where('name', 'regexp', "$search");
        }

        if ($relation) {
            $program = $program->with($relation);
        }

        return $program->paginate($paginate);
    }
}
