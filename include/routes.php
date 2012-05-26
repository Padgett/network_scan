<?php
/*** ROUTING DEFINES ***/
$api->get('/', 'home');
$api->get('/location/register/:client_name/:location_name', 'register');
$api->get('/location/register/', 'home');
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

function register($client_name,$location_name) {
  $loc = new Location(getConnection(), $api);
  if (empty($client_name) || empty($location_name)) {
    echo '{"error":{"text":Registering requires client_name and location_name.}}';
  } else {
    try {
      $new_info = $loc->register($client_name,$location_name);
      echo '{"info": '.json_encode($new_info).'}';
    } catch (Exception $e) {
      echo '{"error":{"text":'.$e->getMessage().'}}';
    }
  }
}

function check_login() {
  $sess = new Session(getConnection(),$api);
  try {
    return $sess->is_logged_in();
  } catch (PDOException $e) {
    echo '{"error":{"text":'.$e->getMessage().'}}';
  }
}

function login($client,$location,$api) {
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
