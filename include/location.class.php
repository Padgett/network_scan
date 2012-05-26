<?php
require_once('include/class.uuid.php');

class Location {
  protected $db;
  protected $api;
  private $client_name;
  private $client_id;
  private $client_uuid;
  private $location_name;
  private $location_id;
  private $location_uuid;
  
  public function __construct($db,&$api,$info = array()) {
    $this->db = $db;
    $this->api = $api;
    if (!empty($info['client_name']))
      $this->client_name = $info['client_name'];
    if (!empty($info['client_id']))
      $this->client_id = $info['client_id'];
    if (!empty($info['client_uuid']))
      $this->client_uuid = $info['client_uuid'];
    if (!empty($info['location_name']))
      $this->location_name = $info['location_name'];
    if (!empty($info['location_id']))
      $this->location_id = $info['location_id'];
    if (!empty($info['location_uuid']))
      $this->location_uuid = $info['location_uuid'];
  }

  //Registers a new client location or returns UUIDs if already exists
  public function register($client_name,$location_name) {
    $this->client_name = $client_name;
    $this->location_name = $location_name;
    if (!empty($this->client_name) || !empty($this->location_name)) {
      //First we'll generate the UUIDs      
      $this->generate_client_uuid();
      $this->generate_location_uuid();
      
      //Check for matching UUIDs in the db
      $client_sql = "SELECT * FROM clients WHERE uuid = :client_uuid";
      $stmt = $this->db->prepare($client_sql);
      $stmt->bindParam("client_uuid", $this->client_uuid);
      $stmt->execute();
      $client = $stmt->fetchObject();
      $stmt = null;
      
      $location_sql = "SELECT * FROM locations WHERE uuid = :location_uuid";
      $stmt = $this->db->prepare($location_sql);
      $stmt->bindParam("location_uuid", $this->location_uuid);
      $stmt->execute();
      $location = $stmt->fetchObject();
      $stmt = null;
      
      //If no match, add
      if (empty($client)) {
        $sql = "INSERT INTO clients (name,uuid) VALUES (:client_name,:client_uuid)";
        try {
          $stmt = $this->db->prepare($sql);
          $stmt->bindParam("client_name", $this->client_name);
          $stmt->bindParam("client_uuid", $this->client_uuid);
          $stmt->execute();
          $this->client_id = $this->db->lastInsertId();
          if (empty($this->client_id)) {
            throw new Exception('Could not add client. Unknown error.');
          }
        } catch(PDOException $e) {
          throw new Exception('Could not add client. Database Error. '.$e->getMessage(), null, $e);
        } catch (Exception $e) {
          throw new Exception($e->getMessage(), null, $e);
        }
      } else {
        //Set our vars
        $this->client_name = $client->name;
        $this->client_id = $client->id;
        $this->client_uuid = $client->uuid;
      }
      
      if (empty($location)) {
        $sql = "INSERT INTO locations (name,client_id,uuid) VALUES (:location_name,:client_id,:location_uuid)";
        try {
          $stmt = $this->db->prepare($sql);
          $stmt->bindParam("location_name", $this->location_name);
          $stmt->bindParam("client_id", $this->client_id);
          $stmt->bindParam("location_uuid", $this->location_uuid);
          $stmt->execute();
          $this->location_id = $this->db->lastInsertId();
          if (empty($this->location_id)) {
            throw new Exception('Could not add location. Unknown error.');
          }
        } catch(PDOException $e) {
          throw new Exception('Could not add location. Database Error. '.$e->getMessage(), null, $e);
        } catch (Exception $e) {
          throw new Exception($e->getMessage(), null, $e);
        }
      } else {
        //Set our vars
        $this->location_name = $location->name;
        $this->location_id = $location->id;
        $this->location_uuid = $location->uuid;
      }
      
      //Return info array
      return array('client_name' => $this->client_name, 'client_uuid' => $this->client_uuid, 
        'location_name' => $this->location_name, 'location_uuid' => $this->location_uuid);
    } else {
      throw new Exception('Registration requires client_name and location_name. 1');
    }
  }
  
  //Generate Location UUID
  private function generate_location_uuid() {
    $this->location_uuid = $sha1  = UUID::generate(UUID::UUID_NAME_SHA1, UUID::FMT_STRING,
        $this->location_name, $this->client_uuid);
  }
  
  //Generate Client UUID
  private function generate_client_uuid() {
    $this->client_uuid  = UUID::generate(UUID::UUID_NAME_SHA1, UUID::FMT_STRING,
        $this->client_name);
  }
  
  //Validate Location UUID
  private function validate_location_uuid() {
    
  }
  
  //Validate Client UUID
  private function validate_client_uuid() {
    
  }
}
?>
