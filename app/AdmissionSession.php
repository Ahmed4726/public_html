<?php

namespace App;

use App\Traits\SortingOrderAble;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Route;

class AdmissionSession extends Model
{
    use SortingOrderAble;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Boot method for model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($session) {
            UserAdmissionSession::whereAdmissionSessionId($session->id)->delete();
        });

        static::addGlobalScope('defaultQuery', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::student-email-apply::list') {
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('STUDENT-EMAIL-MANAGE')) {
                    $admissionSession = UserAdmissionSession::whereUserId(Auth::user()->id)->get(['admission_session_id'])->pluck(['admission_session_id']);
                    $builder->whereIn('id', $admissionSession);
                }
            }
        });
    }
}
