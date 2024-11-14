<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherDesignation extends Model
{
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'designation_id');
    }
}
