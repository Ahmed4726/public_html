<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkType extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class, 'type_id');
    }
}
