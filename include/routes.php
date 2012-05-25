<?php
/*** ROUTING DEFINES ***/
$api->get('/', 'home');
$api->get('/location/login/:uuid', 'login');
$api->delete('/location/:uuid', 'location_delete');
$api->post('/system', 'system_add');
$api->put('/system/:uuid', 'system_update');
$api->delete('/system/:uuid', 'system_delete');
/*** END ***/

/*** ROUTING FUNCTIONS ***/
function home() {
  echo 'Please refer to the documentation.<br /><br />';
  echo date('m/d/Y h:i:s');
}

function login($uuid) {
  
}

function location_delete($uuid) {
  
}

function system_add() {
  
}

function system_update($uuid) {
  
}

function system_delete() {
  
}
/*** END ***/
?>
