<?php

namespace App\Controllers;

use \Slim\Views\Twig as View;
use App\Models\User;

/**
* The home page controller class
*/
class HomeController extends Controller
{
	
	public function index($request, $response)
	{
		//return $this->view->render($response, 'home.twig');
	}
}