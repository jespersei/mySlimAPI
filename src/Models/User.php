<?php

namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Models\Clock;

class User extends Eloquent
{
	protected $fillable = ['username','email','password','salt'];
	protected $salt;
	//public $timestamps = false;

    public function clock() 
    { 
        return User::hasMany('Models\Clock'); 
    }

    public function setPasswordAttribute($value)
    {
        $this->salt = sha1($this->attributes['username'].'-'.strtotime(date('Y-m-d H:i:s')));

        $this->attributes['password'] = sha1($this->salt.$value);
        $this->attributes['salt'] = sha1($this->salt.$value);
    }

    public function setSaltAttribute($value)
    {
        $this->attributes['salt'] = $this->salt;
    }

	public function setSessionTokentAttribute($value)
    {
        $this->attributes['salt'] = $this->salt;
    }
}