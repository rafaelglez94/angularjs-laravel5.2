<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username','password','image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function setPasswordAttribute($password='')
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function getImageAttribute()
    {
        $image = $this->attributes['image'];
        if ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL))
                return $image;
            return asset($this->attributes['image']);
        }
        return $this->attributes['image'];
    }
}
