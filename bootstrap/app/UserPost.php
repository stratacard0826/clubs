<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPost extends Model
{
     use SoftDeletes;

     /**
     * Set Relationship to gender table
     *
     * @param  string  $value
     * @return void
     */
    public function attachments()
    {
        return $this->hasMany("App\PostAttachment", "post_id", "id");
    }

     public function followed()
    {
        return $this->hasMany("App\PostAttachment", "post_id", "id");
    }
}
