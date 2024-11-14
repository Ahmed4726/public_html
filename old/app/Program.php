<?php

namespace App;

use App\Traits\SortingOrderAble;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use SortingOrderAble;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
}
