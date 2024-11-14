<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class LegalCertificate extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    private static function getPaginationNumber ()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeInfo()
    {
        return $this->belongsTo('App\LegalCertificateType', 'type_id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            $certificate->serial = static::max('serial')+1;
        });

        static::deleting(function ($certificate) {
            if($certificate->path) Storage::delete("public/image/certificate/$certificate->path");
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('serial', 'desc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $typeID
     * @param bool $fromDate
     * @param bool $toDate
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter ($search = false, $typeID = false, $fromDate = false, $toDate = false, $paginate = false, $relation = false)
    {
        if(!$paginate)
            $paginate = self::getPaginationNumber();

        $certificate = (new LegalCertificate())->newQuery();

        if($fromDate && $toDate) $certificate = $certificate->where('date', '>=',  date('Y-m-d 00:00:00', strtotime($fromDate)))->where('date', '<=', date('Y-m-d 23:59:59', strtotime($toDate)));

        if($typeID) $certificate = $certificate->whereTypeId($typeID);

        if($search) $certificate = $certificate->where(function ($query) use ($search){
            $query->orWhere("name", "regexp", "$search")
                ->orWhere("designation", "regexp", "$search")
                ->orWhere("serial", "regexp", "$search");
        });

        if($relation) $certificate = $certificate->with($relation);

        return $certificate->paginate($paginate);
    }
}
