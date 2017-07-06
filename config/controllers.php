<?php

$container['HomeController'] = function($container) {

	return new \App\Controllers\HomeController($container);

};

$container['AuthController'] = function($container) {

	return new \App\Controllers\Auth\AuthController($container);

};

$container['PasswordController'] = function($container) {

	return new \App\Controllers\Auth\PasswordController($container);

};

$container['UpdateController'] = function($container) {

	return new \App\Controllers\UpdateController($container);

};