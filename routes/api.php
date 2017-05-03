<?php

$app->group('/api/v1', function() {
	
	$this->post('/auth/token', 'AuthController:getToken');

	$this->get('/updates', 'UpdateController:getUpdates');
	$this->get('/updates/{id}', 'UpdateController:getUpdate');
	$this->post('/updates', 'UpdateController:postUpdate');
	$this->put('/updates/{id}', 'UpdateController:putUpdate');
	//$this->delete('/updates/:id', 'UpdateController:deleteUpdate');
	//$this->patch('/updates/:id', 'UpdateController:patchUpdate');
});
