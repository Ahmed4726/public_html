<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('ADMIN', function ($user) {
            return $user->hasRole('ADMIN');
        });

        Gate::define('TEACHER', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('TEACHER'));
        });

        Gate::define('CENTER', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('CENTER'));
        });

        Gate::define('DEPARTMENT', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('DEPARTMENT'));
        });

        Gate::define('DEAN', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('DEAN'));
        });

        Gate::define('HALL', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('HALL'));
        });

        Gate::define('NOC', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('NOC_GO'));
        });

        Gate::define('EVENT', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('EVENT'));
        });

        Gate::define('EVENT-SPECIFIC', function ($user, $eventID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('EVENT') && $user->events()->whereEventId($eventID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('CENTER-SPECIFIC', function ($user, $centerID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('CENTER') && $user->centers()->whereCenterId($centerID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('DEPARTMENT-SPECIFIC', function ($user, $departmentID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('DEPARTMENT') && $user->departments()->whereDepartmentId($departmentID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('DEAN-SPECIFIC', function ($user, $facultyID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('DEAN') && $user->faculties()->whereFacultyId($facultyID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('HALL-SPECIFIC', function ($user, $hallID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('HALL') && $user->halls()->whereHallId($hallID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('TEACHER-SPECIFIC', function ($user, $teacherID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('TEACHER') && $user->teachers()->whereTeacherId($teacherID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('STUDENT-EMAIL-MANAGE-SPECIFIC', function ($user, $admissionSessionID) {
            if ($user->hasRole('ADMIN')) {
                return true;
            } elseif ($user->hasRole('STUDENT-EMAIL-MANAGE') && $user->admissionSessions()->whereAdmissionSessionId($admissionSessionID)->first()) {
                return true;
            }

            return false;
        });

        Gate::define('STUDENT-EMAIL-MANAGE', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('STUDENT-EMAIL-MANAGE'));
        });

        Gate::define('STUDENT-EMAIL-VIEW', function ($user) {
            return ($user->hasRole('ADMIN')) || ($user->hasRole('STUDENT-EMAIL-MANAGE')) || ($user->hasRole('STUDENT-EMAIL-VIEW'));
        });
    }
}
