<?php

namespace App\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Tuupola\Base62;

/**
* The user authentication class
* 
* @filesource Auth.php
* @author Tlotliso Mafantiri
*/
class Auth
{	
	/**
	 * Attempt to sign in a user
	 * 
	 * @param string $email
	 * @param string $password
	 * @return boolean
	 */ 
	public static function attempt($email, $password)
	{
		$user = User::where('email', $email)->first();

		if(!$user) {
			return false;
		}

		if(password_verify($password, $user->password)) {
			return true;
		}

		return false;
	}

	/**
	 * Generate a JSON Web Token
	 * 
	 * @param User $user
	 * @return string
	 */ 
	public static function generateToken($user)
	{
		$secret = getenv("JWT_SECRET");

	    $payload = [
	        "iat" => time(),
	        //'nbf'  => time() + 10,
	        //"exp" => time() + 10 + 3600,
	        "jti" => base64_encode(mcrypt_create_iv(32)),
	        'iss'  => $_SERVER['SERVER_NAME'],
	        'user' => [                  	// Data related to the signer user
	            'id'   => $user['id'], 		// userid from the users table
	            'email' => $user['email'], 	// User email
	        ]
	    ];

		$token = JWT::encode($payload, $secret, "HS256");

		return $token;
	}

	/**
	 * Log out the user
	 */
	public function logout()
	{
		
	}
}