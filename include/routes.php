<?php
/*** ROUTING DEFINES ***/
$api->get('/', 'home');
$api->get('/location/register/', 'home');
$api->get('/location/register/:client_name/:location_name', 'register');
$api->post('/system', 'check_registration', 'system_post');
/*
 * For future implementation:
 * $api->delete('/location/:uuid', 'check_login', 'location_delete');
 * $api->put('/system/:uuid', 'check_login', 'system_update');
 * $api->delete('/system/:uuid', 'check_login', 'system_delete');
 */
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
      $_SESSION['location'] = $new_info;
      echo '{"registered": '.json_encode($new_info).'}';
    } catch (Exception $e) {
      echo '{"error":{"text":'.$e->getMessage().'}}';
    }
  }
}

function check_registration() {
  $requestObj = Slim::getInstance()->request();
  $body = $requestObj->getBody();
  $request = json_decode($body);
  $loc = new Location(getConnection(), $api);
  if (empty($request->client_name) || empty($request->location_name)) {
    echo '{"error":{"text":client_name and location_name are required.}}';
  } else {
    try {
      $new_info = $loc->register($request->client_name,$request->location_name);
      $_SESSION['location'] = $new_info;
    } catch (Exception $e) {
      echo '{"error":{"text":'.$e->getMessage().'}}';
    }
  }
}

function system_post() {
  $requestObj = Slim::getInstance()->request();
  $body = $requestObj->getBody();
  $request = json_decode($body);
  $loc = new Location(getConnection(),$api,$_SESSION['location']);
  $sys = new System(getConnection(),$api,$loc);
  try {
    $return = $sys->post($request);
    echo '{"success":'.json_encode($return).'}';
  } catch (Exception $e) {
    echo '{"error":{"text":'.$e->getMessage().'}}';
  }
}

function check_login() {
  $requestObj = Slim::getInstance()->request();
  $body = $requestObj->getBody();
  $request = json_decode($body);
  $loc = new Location(getConnection(), $api);
  try {
    $loc->check_login($request);
  } catch (PDOException $e) {
    //echo '{"error":{"text":'.$e->getMessage().'}}';
    //return false;
    $api->halt(500, '{"error":{"text":'.$e->getMessage().'}}');
  } catch (Exception $e) {
    $api->halt(403, '{"error":{"text":'.$e->getMessage().'}}');
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

function system_update() {
  
}

function system_delete() {
  
}
/*** END ***/
?>
