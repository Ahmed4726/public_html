<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class DiscussionTopicFile extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($file) {
            if($file->path) {
                $discussion = DiscussionTopic::find($file->discussion_topic_id);
                Storage::delete("public/image/discussion/$discussion->id/$file->path");
            }
        });
    }
}
