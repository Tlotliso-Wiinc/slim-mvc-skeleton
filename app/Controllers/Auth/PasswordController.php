<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{

	/**
	 * Display change user's password page
	 * 
	 * @param Object $request
	 * @param Object $response
	 * @return \Slim\Views\Twig
	 */
	public function getChangePassword($request, $response)
	{
		return $this->view->render($response, 'auth/password/change.twig');
	}

	/**
	 * Change a user's password
	 * 
	 * @param Object $request
	 * @param Object $response
	 * @return Object $response
	 */
	public function postChangePasswordOnWeb($request, $response)
	{

		$validation = $this->validator->validate($request, [
			'password_old' => v::noWhiteSpace()->notEmpty()->matchesPassword($this->auth->user()->password),
			'password' => v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		$this->auth->user()->setPassword($request->getParam('password'));

		$this->flash->addMessage('info', 'Your password was changed.');

		return $response->withRedirect($this->router->pathFor('main'));
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
		} else {
			$resp['code'] = 401;
			$resp['status'] = 'Unauthorized';
			$resp['success'] = false;
			$resp['message'] = 'The old password is incorrect!';

			return $response->withStatus(401)
			        ->withHeader("Content-Type", "application/json")
			        ->write(json_encode($resp, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
		}
	}
}