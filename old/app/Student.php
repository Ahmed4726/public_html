<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admission_session_id',
        'name',
        'department',
        'roll',
        'registration',
        'hall',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'admission_session_id' => 'integer',
    ];

    public function admissionSession()
    {
        return $this->belongsTo(\App\AdmissionSession::class);
    }

    public function search($search)
    {
        return self::where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%');
        });
    }
}
