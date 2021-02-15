<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUser extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $appends = ["type_name"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone' , 'user_type', 'code', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Set Relationship to gender table
     *
     * @param  string  $value
     * @return void
     */
    public function userType()
    {
        return $this->belongsTo("App\AdminUserType", "user_type", "id");
    }

    /**
     * Set Relationship to gender table
     *
     * @param  string  $value
     * @return void
     */
    public function getTypeNameAttribute()
    {
        return $this->userType->name;
    }

    /**
     * Set Relationship to gender table
     *
     * @param  string  $value
     * @return void
     */
    public function getisAdminAttribute()
    {
        return $this->userType->name;
    }

    /**
     * Check  admin user type
     *
     * @param  string  $value
     * @return void
     */
    public function isAdmin()
    {
        return $this->userType->code == "admin";
    }

    /**
     * Check club user type
     *
     * @param  string  $value
     * @return void
     */
    public function isClub()
    {
        return $this->userType->code == "club";
    }

    /**
     * Check admin user type
     *
     * @param  string  $value
     * @return void
     */
    public function isSubAdmin()
    {
        return $this->userType->code == "subadmin";
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

}
