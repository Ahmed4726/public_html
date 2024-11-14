<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laralum;
use File;
use App\Notifications\WelcomeMessage;
use App\Notifications\AccountActivation;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $dates = ['last_login_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_login_at', 'last_login_ip', 'last_login_device', 'last_login_operating'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return mixed
     */
    private static function getPaginationNumber()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
    * Mutator to capitalize the name
    *
    * @param mixed $value
    */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    /**
    * Returns all the roles from the user
    *
    */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_roles');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function faculties()
    {
        return $this->belongsToMany('App\Faculty', 'user_faculties');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments()
    {
        return $this->belongsToMany('App\Department', 'user_departments');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function centers()
    {
        return $this->belongsToMany('App\Center', 'user_centers');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function halls()
    {
        return $this->belongsToMany('App\Hall', 'user_halls');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachers()
    {
        return $this->belongsToMany('App\Teacher', 'user_teachers');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'user_events')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admissionSessions()
    {
        return $this->belongsToMany(AdmissionSession::class, 'user_admission_sessions')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function myRoles()
    {
        return $this->belongsToMany('App\Role', 'user_roles');
    }

    /**
    * Returns true if the user has access to laralum
    *
    */
    /*public function isAdmin()
    {
        return $this->hasPermission('laralum.access');
    }*/

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('ADMIN');
    }

    /**
     * @param $roleID
     * @return mixed
     */
    public function existRole($roleID)
    {
        return $this->roles->contains(function ($value) use ($roleID) {
            return $value->id == $roleID;
        });
    }

    /**
     * @param $slug
     * @return bool
     */
    public function hasPermission($slug)
    {
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $perm) {
                if ($perm->slug == $slug) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
    * Returns true if the user has the role
    *
    * @param string $name
    */
    /*public function hasRole($name)
    {
        foreach($this->roles as $role) {
            if($role->name == $name) {
                return true;
            }
        }
        return false;
    }*/

    /**
     * @param $name
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->type == $name) {
                return true;
            }
        }
        return false;
    }

    /**
    * Returns all the blogs owned by the user
    *
    */
    public function blogs()
    {
        return $this->hasMany('App\Blog');
    }

    /**
     * @param $id
     * @return bool
     */
    public function has_blog($id)
    {
        foreach ($this->roles as $role) {
            foreach (Laralum::blog('id', $id)->roles as $b_role) {
                if ($role->id == $b_role->id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function owns_blog($id)
    {
        if ($this->id == Laralum::blog('id', $id)->user_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Returns all the posts from the user
    *
    */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
     * @param $id
     * @return bool
     */
    public function owns_post($id)
    {
        if ($this->id == Laralum::post('id', $id)->author->id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param null $size
     * @return string
     */
    public function avatar($size = null)
    {
        $file = Laralum::avatarsLocation() . '/' . md5($this->email);
        $file_url = asset($file);
        if (File::exists($file)) {
            return $file_url;
        } else {
            return Laralum::defaultAvatar();
        }
    }

    /**
    * Returns all the documents from the user
    *
    */
    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    /**
    * Returns all the social accounts from the user
    *
    */
    public function socials()
    {
        return $this->hasMany('App\Social');
    }

    /**
     * @param $provider
     * @return bool
     */
    public function hasSocial($provider)
    {
        foreach ($this->socials as $social) {
            if ($social->provider == $provider) {
                return true;
            }
        }
        return false;
    }

    /**
    * Sends the welcome email notification to the user
    *
    */
    public function sendWelcomeEmail()
    {
        return $this->notify(new WelcomeMessage($this));
    }

    /**
    * Sends the activation email notification to the user
    *
    */
    public function sendActivationEmail()
    {
        return $this->notify(new AccountActivation($this));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stateInfo()
    {
        return $this->belongsTo('App\UserType', 'state_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            UserCenter::whereUserId($user->id)->delete();
            UserDepartment::whereUserId($user->id)->delete();
            UserHall::whereUserId($user->id)->delete();
            UserFaculty::whereUserId($user->id)->delete();
            UserEvent::whereUserId($user->id)->delete();
            UserAdmissionSession::whereUserId($user->id)->delete();
            $user->roles()->detach();

            if ($userTeacher = UserTeacher::whereUserId($user->id)->first()) {
                UserTeacher::whereUserId($user->id)->delete();
                Teacher::find($userTeacher->teacher_id)->delete();
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $stateID
     * @param bool $roleID
     * @param bool $facultyID
     * @param bool $departmentID
     * @param bool $centerID
     * @param bool $hallID
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter($search = false, $stateID = false, $roleID = false, $facultyID = false, $departmentID = false, $centerID = false, $hallID = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $user = (new User())->newQuery();

        if ($stateID) {
            $user = $user->whereStateId($stateID);
        }

        if ($roleID) {
            $user = $user->whereHas('roles', function ($query) use ($roleID) {
                $query->where('id', $roleID);
            });
        }

        if ($facultyID) {
            $user = $user->whereHas('faculties', function ($query) use ($facultyID) {
                $query->where('id', $facultyID);
            });
        }

        if ($departmentID) {
            $user = $user->whereHas('teachers', function ($query) use ($departmentID) {
                $query->where('department_id', $departmentID);
            })->orWhereHas('departments', function ($query) use ($departmentID) {
                $query->where('id', $departmentID);
            });

            /*$user = $user->whereHas('departments', function ($query) use ($departmentID){
                $query->where('id', $departmentID);
            });*/
        }

        if ($centerID) {
            $user = $user->whereHas('centers', function ($query) use ($centerID) {
                $query->where('id', $centerID);
            });
        }

        if ($hallID) {
            $user = $user->whereHas('halls', function ($query) use ($hallID) {
                $query->where('id', $hallID);
            });
        }

        if ($search) {
            $user = $user->where(function ($query) use ($search) {
                $query->orWhere('name', 'regexp', "$search")
                ->orWhere('email', 'regexp', "$search");
            });
        }

        if ($relation) {
            $user = $user->with($relation);
        }

        return $user->paginate($paginate);
    }
}
