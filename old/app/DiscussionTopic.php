<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class DiscussionTopic extends Model
{
    /**
     * @var string
     */
//    protected $table = 'user_roles';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $appends = ['real_image_path'];

    /**
     * @return mixed
     */
    private static function getPaginationNumber ()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @return string
     */
    public function getRealImagePathAttribute()
    {
        return ($this->image_url) ? asset("storage/image/discussion/$this->image_url") : asset('images/discussion-default.png');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * @param $value
     * @return string
     */
    public function getPublishDateAttribute($publishDate)
    {
        return Carbon::parse($publishDate)->format('M d, Y');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(DiscussionTopicFile::class);
    }

    /**
     * @param string $departmentID
     * @param bool $take
     * @return mixed
     */
    public static function featured($departmentID = 'global', $take = false)
    {
        $query = static::whereEnabled(1)->whereFeaturedNews(1);

        if($departmentID == 'global' || !$departmentID)
            $query->whereNull('department_id');
        else
            $query->whereDepartmentId($departmentID);

        if($take)
            $query->take($take);

        return $query->get();
    }

    /**
     * @param bool $take
     * @return mixed
     */
    public static function spotlights($take = false)
    {
        $query = static::whereEnabled(1)->whereSpotlight(1);

        if($take)
            $query->take($take);

        return $query->get();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($discussion) {
            if($discussion->image_url) Storage::delete("public/image/discussion/$discussion->image_url");

            $files = DiscussionTopicFile::whereDiscussionTopicId($discussion->id)->get();
            if($files->isNotEmpty()){
                foreach ($files as $file)
                    $file->delete();
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $departmentId
     * @param bool $eventId
     * @param bool $status
     * @param bool $fromDate
     * @param bool $toDate
     * @param bool $paginate
     * @param bool $relation
     * @param array $options
     * @param bool $restrictEvent
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $departmentId = false, $eventId = false, $status = false, $fromDate = false, $toDate = false, $paginate = false, $relation = false, $options = array(), $restrictEvent = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $discussions = (new DiscussionTopic())->newQuery();

        if($eventId) $discussions = $discussions->whereEventId($eventId);

        if($fromDate && $toDate) $discussions = $discussions->where('publish_date', '>=',  date('Y-m-d 00:00:00', strtotime($fromDate)))->where('publish_date', '<=', date('Y-m-d 23:59:59', strtotime($toDate)));

        if($departmentId == 'global'  || !$departmentId) $discussions = $discussions->whereNull('department_id');
        else if($departmentId) $discussions = $discussions->whereDepartmentId($departmentId);

        if($status == 'disable') $discussions = $discussions->whereEnabled(0);
        elseif($status != false && $status) $discussions = $discussions->whereEnabled($status);

        if($search) $discussions = $discussions->where("title", "like",  '%' . $search. '%');

        if(!empty($options)){
            foreach ($options as $index => $option)
                $discussions = $discussions->where($index, $option);
        }

        if($restrictEvent){
            $discussions = $discussions->whereHas('event', function ($query) use ($restrictEvent){
                $query->whereIn('id', $restrictEvent);
            });
        }

        if($relation) $discussions = $discussions->with($relation);

        return $discussions->paginate($paginate);
    }
}
