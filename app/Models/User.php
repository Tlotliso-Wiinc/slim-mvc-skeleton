<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

/**
 * The user model class
 */ 
class User extends Model
{
	/**
	 * @var array $fillable: an array of table fields that can be altered
	 */ 
	protected $fillable = [
		'firstname', 'lastname', 'email', 'password'
	];

	/**
	 * @var array $hidden
	 */ 
	protected $hidden = ['password'];

	/**
	 * Set a new password
	 * 
	 * @param string $password
	 * @return void
	 */ 
	public function setPassword($password)
	{
		$this->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}	
}