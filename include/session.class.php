<?php
class Session {
  protected $db;
  
  public function __construct($db) {
    $this->db = $db;
  }
  
  public function login($client,$location) {
    return true;
  }
  
  public function check_login() {
    return true;
  }
}
?>
