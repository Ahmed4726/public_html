<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeacherPublication extends Model
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

    public function typeInfo()
    {
        return $this->belongsTo('App\TeacherPublicationType', 'teacher_publication_type_id');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($publication) {
            $maxAvailableSortNumber = static::whereTeacherId($publication->teacher_id)->whereTeacherPublicationTypeId($publication->teacher_publication_type_id)->max('sorting_order');
            if($maxAvailableSortNumber != '9999') $publication->sorting_order = $maxAvailableSortNumber+1;
        });

        static::updating(function ($publication) {
            if($publication->getOriginal('teacher_publication_type_id') != $publication->teacher_publication_type_id){
                $maxAvailableSortNumber = static::where('id', '!=', $publication->id)->whereTeacherId($publication->teacher_id)->whereTeacherPublicationTypeId($publication->teacher_publication_type_id)->max('sorting_order');
                if($maxAvailableSortNumber != '9999') $publication->sorting_order = $maxAvailableSortNumber+1;
                else $publication->sorting_order = 9999;
            }
        });*/

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('teacher_publication_type_id', 'asc')->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $teacherID
     * @param bool $typeID
     * @param bool $status
     * @param bool $orderBy
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $teacherID = false, $typeID = false, $status = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $publication = (new TeacherPublication())->newQuery();

        if($teacherID) $publication = $publication->whereTeacherId($teacherID);

        if($typeID) $publication = $publication->whereTeacherPublicationTypeId($typeID);

        if($status == 'inactive') $publication = $publication->whereStatus(0);
        elseif($status != false && $status) $publication = $publication->whereStatus($status);

        if($search) $publication = $publication->where(function ($query) use ($search){
            $query->orWhere("name", "regexp", "$search")
                ->orWhere("description", "regexp", "$search");
        });

        if($relation) $publication = $publication->with($relation);

        return $publication->paginate($paginate);
    }
}
