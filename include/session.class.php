<?php
class Session {
  protected $db;
  protected $api;
  
  public function __construct($db,&$api) {
    $this->db = $db;
    $this->api = $api;
  }
  
  public function login($client,$location) {
    //We're just defaulting to true right now
    return true;
  }
  
  public function is_logged_in() {
    return true;
  }
}
?>
