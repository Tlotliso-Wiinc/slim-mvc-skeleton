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

		// generate a token based on the user info
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
			$resp['success'] = false;
			$resp['message'] = 'The email address provided already exists!';

			return $response->withStatus(202)
			        ->withHeader("Content-Type", "application/json")
			        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	 	}

	 	// save the user's info
	 	$user = User::create([
			'firstname' => $request->getParam('firstname'),
			'lastname' => $request->getParam('lastname'),
			'email' => $request->getParam('email'),
			'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
		]);

	 	// generate a token based on the user info
		$token = Auth::generateToken($user);

		$resp['code'] = 201;
		$resp['status'] = 'created';
		$resp['success'] = true;
		$resp['message'] = 'Successfully registered a new user.';
		$resp['token'] = $token;

		return $response->withStatus(201)
		        ->withHeader("Content-Type", "application/json")
		        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	} 

	 /**
	 * Login a user
	 * 
	 * @param Object $request
	 * @param Object $response
	 * @return Object $response
	 */
	public function postSignIn($request, $response)
	{
	 	$auth = Auth::attempt(
	 		$request->getParam('email'),
			$request->getParam('password')
	 	);

	 	if (!$auth) {
	 		$resp['code'] = 202;
			$resp['status'] = 'accepted';
			$resp['success'] = false;
			$resp['message'] = 'Incorrect login credentials!';

			return $response->withStatus(202)
			        ->withHeader("Content-Type", "application/json")
			        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	 	}

	 	// get the user by email
	 	$user = User::where('email', $request->getParam('email'))->first();

	 	// generate the token based on the user info
		$token = Auth::generateToken($user);

		$resp['code'] = 200;
		$resp['status'] = 'ok';
		$resp['success'] = true;
		$resp['message'] = 'Successfully logged in';
		$resp['token'] = $token;

		return $response->withStatus(200)
		        ->withHeader("Content-Type", "application/json")
		        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	} 

	/**
	 * Change a user's password
	 * 
	 * @param Object $request
	 * @param Object $response
	 * @return Object $response
	 */
	public function postChangePassword($request, $response)
	{
		// get the user's id from the decoded JSON Web Token
		$user_id = $this->jwt->user->id;

		// get the user's info
		$user = User::find($user_id);

		if (!$user) {
			$resp['code'] = 400;
			$resp['status'] = 'Bad Request';
			$resp['success'] = false;
			$resp['message'] = 'Oops! something is not right!';

			return $response->withStatus(400)
			        ->withHeader("Content-Type", "application/json")
			        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
		}

		if (password_verify($request->getParam('old_password'), $user->password)) {
			$user->setPassword($request->getParam('new_password'));

			$resp['code'] = 200;
			$resp['status'] = 'ok';
			$resp['success'] = true;
			$resp['message'] = 'Successfully updated the password';

			return $response->withStatus(200)
			        ->withHeader("Content-Type", "application/json")
			        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
		}
	}
}