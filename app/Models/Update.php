<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

/**
 * The user model class
 */ 
class Update extends Model
{
	/**
	 * @var array $fillable: an array of table fields that can be altered
	 */ 
	protected $fillable = [
		'message'
	];
	
}