<?php

$app->group('/api/v1', function() {
	$this->post('/auth/token', 'AuthController:getToken');

	$this->get('/updates', 'UpdateController:getUpdates');
	$this->post('/updates', 'UpdateController:postUpdate');
	//$this->put('/updates', 'UpdateController:postUpdate');
});
