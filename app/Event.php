<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function topics()
    {
        return $this->hasMany(DiscussionTopic::class);
    }

    /**
     * @param array $eventID
     * @param bool $departmentID
     * @param int $take
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function listWithTopics(array $eventID, $departmentID = false, $take = 5)
    {
        $eventIDFormatted = implode(',', $eventID);
        $query = (new Event())->newQuery();

        if(!empty($eventID))
            $query->whereIn('id', $eventID);

        if($departmentID){
            $query->with(['topics' => function ($query) use ($departmentID){
                $query->whereDepartmentId($departmentID);
            }]);
        }else{
            $query->with(['topics' => function ($query) use ($departmentID){
                $query->whereNull('department_id');
            }]);
        }

        return $query->orderByRaw("FIELD(id, $eventIDFormatted)")->get()->map(function ($query) use ($take) {
            return $query->setRelation('topics', $query->topics->take($take));
        });
    }
}
