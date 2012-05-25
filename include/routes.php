<?php
/*** ROUTING DEFINES ***/
$api->get('/', 'home');
$api->get('/location/login/:client/:location', 'login');
$api->delete('/location/:uuid', 'check_login', 'location_delete');
$api->post('/system', 'check_login', 'system_add');
$api->put('/system/:uuid', 'check_login', 'system_update');
$api->delete('/system/:uuid', 'check_login', 'system_delete');
/*** END ***/

/*** ROUTING FUNCTIONS ***/
function home() {
  echo 'Please refer to the documentation.<br /><br />';
  echo date('m/d/Y h:i:s');
}

function check_login() {
  $sess = new Session(getConnection());
  try {
    return $sess->check_login();
  } catch (PDOException $e) {
    echo '{"error":{"text":'.$e->getMessage().'}}';
  }
}

function login($client,$location) {
  $sess = new Session(getConnection());
  try {
    $sess->login($client,$location);
  } catch (PDOException $e) {
    echo '{"error":{"text":'.$e->getMessage().'}}';
  } catch (Exception $e) {
    echo '{"error":{"text":Could not login. '.$e->getMessage().'}}';
  }
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
