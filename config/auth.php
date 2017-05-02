<?php

$container['auth'] = function($container) {

	return new \App\Auth\Auth;

};

$container["jwt"] = function ($container) {
    return new StdClass;
};
