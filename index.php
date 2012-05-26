<?php
require_once('include/setup.php');
require_once('include/class.uuid.php');
require_once('include/location.class.php');
require_once('include/system.class.php');
require_once('include/session.class.php');
require_once('include/Slim/Slim.php');

$api = new Slim();

//Include our routes
require_once ('include/routes.php');

//And run
$api->run();
