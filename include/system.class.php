<?php
require_once('include/class.uuid.php');

class System {
  protected $db;
  protected $api;
  
  public function __construct() {
    $this->db = $db;
    $this->api = $api;
  }
  
  public function post($request) {
    if (empty($request->macaddress)) {
      throw new Exception('MAC required.');
    }
    $sql = "INSERT INTO systems (hostname,macaddress,macvendor,os) VALUES
      (:hostname, :macaddress, :macvendor, :os) ON DUPLICATE KEY UPDATE
      hostname = :hostname, os = :os
    ";
    try {
      //First, handle main system info
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam("hostname", $request->hostname);
      $stmt->bindParam("macaddress", $request->macaddress);
      $stmt->bindParam("macvendor", $request->macvendor);
      $stmt->bindParam("os", $request->os);
      $stmt->execute();
      $stmt = null;
      
      //Get the system id for this macaddress
      $sql = "SELECT id FROM systems WHERE macaddress = :macaddress";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam("macaddress", $request->macaddress);
      $system = $stmt->fetchObject();
      $this->id = $system->id;
      $stmt = null;
      if (empty($this->id)) {
        throw new Exception('Could not add system. Unknown error.');
      }

      //Then, handle IPs
      $sql = "INSERT INTO ips (system_id,value) VALUES (:system_id,:value)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam("system_id", $this->id);
      $stmt->bindParam("value", $request->ip);
      $stmt->execute();
      $stmt = null;
      
      //Return our full object
      $sql = "SELECT *, :ip AS ip FORM systems WHERE id = :system_id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam("system_id", $this->id);
      $stmt->bindParam("ip", $request->ip);
      return $stmt->fetchObject();
    } catch(PDOException $e) {
      throw new Exception('Could not add client. Database Error. '.$e->getMessage(), null, $e);
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), null, $e);
    }
  }
}
?>
