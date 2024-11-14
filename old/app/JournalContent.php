<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Storage;

class JournalContent extends Model
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
    private static function getPaginationNumber()
    {
        return (request()->is('admin/*')) ? Setting::getDataByKey('backend_pagination_number') : Setting::getDataByKey('frontend_pagination_number');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function journal()
    {
        return $this->belongsTo('App\Journal');
    }

    protected static function boot()
    {
        parent::boot();

        /*static::creating(function ($content) {
            $content->sorting_order = static::whereJournalId($content->journal_id)->max('sorting_order')+1;
        });*/

        /*static::updating(function ($research) {
            if($research->getOriginal('department_id') != $research->department_id)
                $research->sorting_order = static::whereDepartmentId($research->department_id)->max('sorting_order')+1;
        });*/

        static::deleting(function ($content) {
            if ($content->path) {
                Storage::delete("public/image/journal/$content->path");
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $journalID
     * @param bool $paginate
     * @param bool $relation
     * @param bool $departmentID
     * @param bool $facultyID
     * @param bool $deepSearch
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter($search = false, $journalID = false, $paginate = false, $relation = false, $departmentID = false, $facultyID = false, $deepSearch = false, $options = [])
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $content = (new JournalContent())->newQuery();

        if ($journalID) {
            $content = $content->whereJournalId($journalID);
        }

        if ($search) {
            $content = $content->where(function ($query) use ($search) {
                $query->orWhere('title', 'regexp', "$search")
                ->orWhere('author', 'regexp', "$search")
                ->orWhere('co_author', 'regexp', "$search");
            });
        }

        if ($relation && $departmentID) {
            $content = $content->whereHas('journal', function ($query) use ($departmentID) {
                $query->whereDepartmentId($departmentID);
            });
        }

        if (($relation && $facultyID) && !$departmentID) {
            $content = $content->whereHas('journal', function ($query) use ($facultyID) {
                $query->whereFacultyId($facultyID);
            });
        }

        if ($deepSearch && $relation && $search) {
            $content = $content->orWhereHas('journal', function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            });
        }

        if (!empty($options)) {
            foreach ($options as $index => $option) {
                if ($option) {
                    $content = $content->where($index, $option);
                }
            }
        }

        if ($relation) {
            if ($relation == 'journal') {
                if (!$departmentID && !$facultyID) {
                    $content = $content->withoutGlobalScopes()->with(['journal' => function ($query) {
                        $query->orderBy('faculty_id')->orderBy('department_id')->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
                    }]);
                } else {
                    $content = $content->with('journal');
                }
            } else {
                $content = $content->with($relation);
            }
        }

        return $content->paginate($paginate);
    }
}
