<?php

$container['db'] = function($container) use ($capsule) {
	return $capsule;
};