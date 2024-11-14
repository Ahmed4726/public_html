<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdministrativeMember extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\AdministrativeRole', 'administrative_member_roles', 'member_id', 'role_id');
    }

    /**
     * @param $roleID
     * @return mixed
     */
    public function hasRole($roleID)
    {
        return $this->roles->contains(function ($value) use ($roleID) {
            return $value->id == $roleID;
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($member) {
            if ($member->image_url) {
                \Storage::delete("public/image/administration/$member->image_url");
            }
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
    public static function allWithOptionalFilter($search = false, $typeID = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $roles = (new AdministrativeMember())->newQuery();

        if ($typeID) {
            $roles = $roles->whereTypeId($typeID);
        }

        if ($search) {
            $roles = $roles->where('name', 'regexp', "$search");
        }

        if ($relation) {
            $roles = $roles->with($relation);
        }

        return $roles->paginate($paginate);
    }
}
