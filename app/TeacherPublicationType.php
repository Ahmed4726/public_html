<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TeacherPublicationType extends Model
{
    public function publications()
    {
        return $this->hasMany(TeacherPublication::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc');
        });
    }
}
