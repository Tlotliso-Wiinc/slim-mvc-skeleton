<?php

//use App\Middleware\AuthMiddleware;
//use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index');
$app->get('/home', 'HomeController:index')->setName('home');

$app->group('', function() {

	$this->post('/signup', 'AuthController:postSignUp');
	//$this->post('/signin', 'AuthController:postSignIn');
});

/*
$app->group('', function() {

	$this->post('/auth/password/change', 'PasswordController:postChangePassword');

})->add(new AuthMiddleware($container));
*/