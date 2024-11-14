<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdministrativeRole extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany('App\AdministrativeMember', 'administrative_member_roles', 'role_id', 'member_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($admin) {
            $maxAvailableSortNumber = static::max('sorting_order');
            $admin->sorting_order = $maxAvailableSortNumber+1;
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $typeID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $typeID = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $roles = (new AdministrativeRole())->newQuery();

        if($typeID) $roles = $roles->whereTypeId($typeID);

        if($search) $roles = $roles->where("name", "regexp", "$search");

        if($relation) $roles = $roles->with($relation);

        return $roles->paginate($paginate);
    }
}
