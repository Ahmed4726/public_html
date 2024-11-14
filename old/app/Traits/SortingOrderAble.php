<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortingOrderAble
{
    public static function bootSortingOrderAble()
    {
        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->latest();
        });
    }
}
