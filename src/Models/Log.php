<?php

namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Log extends Eloquent
{
	protected $fillable = ['activity'];
	protected $salt;
}