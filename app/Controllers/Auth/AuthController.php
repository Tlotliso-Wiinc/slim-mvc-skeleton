<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use App\Auth\Auth;

/**
 * The Authentication Controller Class
 * 
 * @filesource AuthController.php
 * @author Tlotliso Mafantiri 
 */ 
class AuthController extends Controller
{
	/**
	 * Get a token for a specific user
	 * 
	 * @param Object $request
	 * @param Object $response
	 * @return Object $response
	 */ 
	public function getToken($request, $response)
	{
		$email = $request->getHeaderLine('PHP_AUTH_USER');
		$user = User::where('email', $email)->first();

		// generate the token based on the user info
		$token = Auth::generateToken($user);

		$resp['code'] = 200;
		$resp['status'] = 'ok';
		$resp['token'] = $token;

		return $response->withStatus(200)
		        ->withHeader("Content-Type", "application/json")
		        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	}

	/**
	 * Register a new user
	 * 
	 * @param Object $request
	 * @param Object $response
	 * @return Object $response
	 */
	 public function postSignUp($request, $response)
	 {
	 	if ($user = User::where('email', $request->getParam('email'))->first()) {
	 		$resp['code'] = 202;
			$resp['status'] = 'accepted';
			$resp['message'] = 'The email address provided already exists!';

			return $response->withStatus(201)
			        ->withHeader("Content-Type", "application/json")
			        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	 	}

	 	$user = User::create([
			'firstname' => $request->getParam('firstname'),
			'lastname' => $request->getParam('lastname'),
			'email' => $request->getParam('email'),
			'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
		]);

		$resp['code'] = 201;
		$resp['status'] = 'created';
		$resp['message'] = 'Successfully registered a new user.';

		return $response->withStatus(201)
		        ->withHeader("Content-Type", "application/json")
		        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	 } 
}