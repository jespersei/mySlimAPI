<?php

namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Models\User;


class Clock extends Eloquent
{
	protected $fillable = ['activity','user_id'];
	protected $salt;

    public $table = 'clock';

    public function user()
    {
    	return $this->belongsTo('Models\User'); 
    }
}