<?php

namespace App;

use App\Pipelines\Builders\DepartmentFilter;
use App\Pipelines\Builders\HallFilter;
use App\Traits\ExtendAble;
use Auth;
use Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Route;

class StudentEmailApply extends Model
{
    use Notifiable, ExtendAble;

    /**
     * Pening Status Text
     *
     * @var string
     */
    public static $pending = 'Pending';

    /**
     * Complete Status Text
     *
     * @var string
     */
    public static $completed = 'Completed';

    /**
     * Reject Status Text
     *
     * @var string
     */
    public static $rejected = 'Rejected';

    /**
     * Checked Status Text
     *
     * @var string
     */
    public static $checked = 'Checked';

    /**
     * Front Side of ID Card Image Maximum Upload Size
     *
     * @var string
     */
    public static $maxIDUploadSize = '200';

    public static $emailDomain = '@juniv.edu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admission_session_id',
        'department_id',
        'program_id',
        'hall_id',
        'global_status_id',
        'registration_number',
        'first_name',
        'middle_name',
        'last_name',
        'contact_phone',
        'existing_email',
        'image',
        'username',
        'password',
        'checked_by',
        'completed_by',
        'rejected_by',
        'checked_at',
        'completed_at',
        'rejected_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'admission_session_id' => 'integer',
        'department_id' => 'integer',
        'program_id' => 'integer',
        'hall_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'checked_at',
        'completed_at',
        'rejected_at'
    ];

    /**
     * Make a name virtual field
     *
     * @return void
     */
    public function getNameAttribute()
    {
        return implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name]));
    }

    /*
     * Check if status is anything.
     *
     * @return bool
     */

    public function isStatus($status)
    {
        return ($this->globalStatus->name == $status) ? true : false;
    }

    /**
     * Check if status is complete
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->isStatus(self::$completed);
    }

    /**
     * Check if status is pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->isStatus(self::$pending);
    }

    /**
     * Check if status is reject.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->isStatus(self::$rejected);
    }

    /**
     * Check if status is checked.
     *
     * @return bool
     */
    public function isChecked()
    {
        return $this->isStatus(self::$checked);
    }

    /**
     * Relation with Admission Sesson Model
     *
     * @return void
     */
    public function admissionSession()
    {
        return $this->belongsTo(\App\AdmissionSession::class);
    }

    /**
     * Relation With Program Model
     *
     * @return void
     */
    public function program()
    {
        return $this->belongsTo(\App\Program::class);
    }

    /**
     * Relation With Department Model
     *
     * @return void
     */
    public function department()
    {
        return $this->belongsTo(\App\Department::class);
    }

    /**
     * Relation With Hall Model
     *
     * @return void
     */
    public function hall()
    {
        return $this->belongsTo(\App\Hall::class);
    }

    /**
     * Relation With GLobal Status Model
     *
     * @return void
     */
    public function globalStatus()
    {
        return $this->belongsTo(GlobalStatus::class);
    }

    /**
     * Relation With GLobal Status Model
     *
     * @return void
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relation With GLobal Status Model
     *
     * @return void
     */
    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    /**
     * Relation With GLobal Status Model
     *
     * @return void
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Relation With GLobal Status Model
     *
     * @return void
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Model Event Fire
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($email) {
            $email->password = Str::random(8);
            $email->global_status_id = $email->global_status_id ?? self::onlyStatus(self::$pending)->id;
        });

        static::deleted(function ($studentEmailApply) {
            (!$studentEmailApply->image) ?: \Storage::delete("public/image/student-email-apply/$studentEmailApply->image");
        });

        static::addGlobalScope('defaultQuery', function (Builder $builder) {
            if (Route::currentRouteName() == 'Laralum::student-email-apply::list') {
                if (!Auth::user()->can('ADMIN') && Auth::user()->can('STUDENT-EMAIL-MANAGE')) {
                    $admissionSession = UserAdmissionSession::whereUserId(Auth::user()->id)->get(['admission_session_id'])->pluck(['admission_session_id']);
                    $builder->whereIn('admission_session_id', $admissionSession);
                }
            }
        });
    }

    /**
     * Name and Email Address During Notification
     *
     * @param [type] $notification
     * @return void
     */
    public function routeNotificationForMail($notification)
    {
        return [$this->existing_email => $this->name];
    }

    /**
     * Retrive Results With Optional Filter if Have Query Params
     *
     * @param boolean $with
     * @param boolean $perPage
     * @return void
     */
    public static function allWithOptionalFilter($with = false, $perPage = false, $onlyRegistrationNumber = true)
    {
        $query = app(Pipeline::class)
                ->send(self::query())
                ->through([
                    DepartmentFilter::class,
                    HallFilter::class
                ])
                ->thenReturn();

        (!$with) ?: $query->with($with);

        (!$admissionSessionId = request()->admission_session_id) ?: $query->whereAdmissionSessionId($admissionSessionId);

        (!$programId = request()->program_id) ?: $query->whereProgramId($programId);

        $query->when($range = request()->range, function ($query, $range) {
            $query->whereBetween('created_at', Helper::dateRangeTextToArray($range));
        });

        (!$statusID = request()->status_id) ?: $query->whereGlobalStatusId($statusID);

        if ($search = request()->search) {
            if ($onlyRegistrationNumber) {
                $query = $query->whereRegistrationNumber($search)->paginate($perPage);
                return ($query->total() == 0) ? self::allWithOptionalFilter($with, $perPage, false) : $query;
            }

            $query->where(function ($query) use ($search) {
                $query->orWhere('first_name', 'like', '%' . $search . '%')
                ->orWhere('middle_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('contact_phone', 'like', '%' . $search . '%')
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhere('existing_email', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Mark as checked with additional info
     *
     * @return void
     */
    public function markChecked()
    {
        $this->global_status_id = self::onlyStatus(self::$checked)->id;
        $this->checked_by = auth()->id() ?? null;
        $this->checked_at = now();
        return;
    }

    /**
     * Mark as completed with additional info
     *
     * @return void
     */
    public function markCompleted()
    {
        $this->global_status_id = self::onlyStatus(self::$completed)->id;
        $this->completed_by = auth()->id();
        $this->completed_at = now();
        return;
    }

    /**
     * Mark as rejected with additional info
     *
     * @return void
     */
    public function markRejected()
    {
        $this->global_status_id = self::onlyStatus(self::$rejected)->id;
        $this->rejected_by = auth()->id();
        $this->rejected_at = now();
        return;
    }
}
