<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
	use SoftDeletes;
   protected $fillable = ['post_id','user_id','parent_id','like_count','text','created_by','updated_by'];

   /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo('App\User',"user_id", "id");
    }
}
