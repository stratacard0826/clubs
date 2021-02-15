<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FullTextSearch;

class Event extends Model
{
    use SoftDeletes, FullTextSearch;

    /**
     * The attributes that are searchable with full text
     *
     * @var array
     */
    protected $searchable = [
            'name',
            
        ];

    /**
     * Set Relationship to Event Type table
     *
     * @param  string  $value
     * @return void
     */
    public function eventType()
    {
        return $this->belongsTo("App\EventType", "event_type", "id");
    }

    /**
     * Set Relationship to Club User table
     *
     * @param  string  $value
     * @return void
     */
    public function clubUser()
    {
        return $this->belongsTo("App\AdminUser", "club_user", "id");
    }

        /**
     * Set Relationship to follower table
     *
     * @param  string  $value
     * @return void
     */
    public function bookedUsers()
    {
        return $this->belongsToMany("App\User", "event_books", "event_id", "user_id")->wherePivot('status', 1)->wherePivot('deleted_at', NULL)->withTimestamps()->withPivot('id');
    }
}
