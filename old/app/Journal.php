<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
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
    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents()
    {
        return $this->hasMany('App\JournalContent');
    }

    /**
     * @param bool $search
     * @param bool $journalID
     * @param bool $orderBy
     * @param bool $paginate
     * @param bool $relation
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function contentWithOptionalFilter($search = false, $orderBy = false, $paginate = false, $relation = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        if (!$orderBy) {
            $content = $this->contents()->orderBy('id', 'desc');
        } else {
            $content = $this->contents()->orderBy('id', $orderBy);
        }

        if ($search) {
            $content = $content->where(function ($query) use ($search) {
                $query->orWhere('title', 'regexp', "$search")
                ->orWhere('author', 'regexp', "$search")
                ->orWhere('co_author', 'regexp', "$search");
            });
        }

        if ($relation) {
            $content = $content->with($relation);
        }

        return $content->paginate($paginate);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($journal) {
            foreach (JournalContent::whereJournalId($journal->id)->get() as $content) {
                $content->delete();
            }
        });

        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('sorting_order', 'asc')->orderBy('id', 'desc');
        });
    }

    /**
     * @param bool $search
     * @param bool $departmentID
     * @param bool $facultyID
     * @param bool $paginate
     * @param bool $relation
     * @param array $restrict
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function allWithOptionalFilter($search = false, $departmentID = false, $facultyID = false, $paginate = false, $relation = false, $restrict = [], $customOrderBy = false)
    {
        if (!$paginate) {
            $paginate = self::getPaginationNumber();
        }

        $journal = (new Journal())->newQuery();

        if ($facultyID) {
            $journal = $journal->whereFacultyId($facultyID);
        }

        if ($departmentID) {
            $journal = $journal->whereDepartmentId($departmentID);
        }

        if ($search) {
            $journal = $journal->where('title', 'regexp', "$search");
        }

        if (!empty($restrict)) {
            $condition = 'whereHas';
            foreach ($restrict as $relation => $value) {
                $journal = $journal->$condition("$relation", function ($query) use ($value) {
                    $query->whereIn('id', $value);
                });
                $condition = 'orWhereHas';
            }
        }

        if ($customOrderBy) {
            $journal = $journal->orderBy('faculty_id')->orderBy('department_id');
        }

        if ($relation) {
            $journal = $journal->with($relation);
        }

        return $journal->paginate($paginate);
    }
}
