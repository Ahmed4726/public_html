<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
    	return $this->belongsToMany('App\User');
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('super-user', function (Builder $builder) {
            $builder->whereNotIn('id', [8, 9, 11, 12, 13, 14, 16]);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
    	return $this->belongsToMany('App\Permission');
    }

    /**
     * @param $slug
     * @return bool
     */
    public function hasPermission($slug)
    {
        foreach($this->permissions as $perm) {
            if($perm->slug == $slug) {
                return true;
            }
        }
        return false;
    }
}
