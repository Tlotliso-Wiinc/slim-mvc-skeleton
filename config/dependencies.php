<?php

$container['flash'] = function($container) {

	return new \Slim\Flash\Messages;

};

$container['validator'] = function($container) {

	return new App\Validation\Validator;

};

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};