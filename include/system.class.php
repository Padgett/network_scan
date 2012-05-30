<?php
require_once('include/class.uuid.php');

class System {
  protected $db;
  protected $api;
  protected $loc;
  
  public function __construct($db,&$api,&$loc) {
    $this->db = $db;
    $this->api = $api;
    $this->loc = $loc;
  }
  
  public function post($request) {
    if (empty($request->macaddress)) {
      throw new Exception('MAC required.');
    }
    $sql = "INSERT INTO systems (location_id,hostname,macaddress,macvendor,os) VALUES
      (:location_id, :hostname, :macaddress, :macvendor, :os) ON DUPLICATE KEY UPDATE
      hostname = :hostname, os = :os, id = LAST_INSERT_ID(id)
    ";
    try {
      //First, handle main system info
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam("location_id", $_SESSION['location']['location_id']); //$this->loc->get_location_id
      $stmt->bindParam("hostname", $request->hostname);
      $stmt->bindParam("macaddress", $request->macaddress);
      $stmt->bindParam("macvendor", $request->macvendor);
      $stmt->bindParam("os", $request->os);
      $stmt->execute();
      $this->id = $this->db->lastInsertId();
      if (empty($this->id)) {
        throw new Exception('Could not add system. Unknown error.');
      }
      $stmt = null;

      //Then, handle IPs
      $sql = "INSERT INTO ips (system_id,value) VALUES (:system_id,:value)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam("system_id", $this->id);
      $stmt->bindParam("value", $request->ip);
      $stmt->execute();
      $stmt = null;
      
      //Return our full object
      $system = array('hostname' => $request->hostname, 'macaddress' => $request->macaddress, 
        'macvendor' => $request->macvendor, 'ip' => $request->ip);
      return array_merge($_SESSION['location'],$system);
    } catch(PDOException $e) {
      throw new Exception('Could not add client. Database Error. '.$e->getMessage(), null, $e);
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), null, $e);
    }
  }
}
?>
