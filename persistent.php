<?

require_once("../persistence_manager.php");

abstract class Persistent{
  private $id;
  private $persistanceManager;
  private $db;
  private $objectTable;

  final function __construct($id=null){
    $this->id = $id;
    $this->persistanceManager = PersistenceManager::getInstance();
    $this->db = DatabaseConnection::getInstance();
    $this->objectTable = $this->persistanceManager->getMainObjectTableName();
  }
  
  final function getID(){
    return $this->id;
  }
  
  /**
  Perzisztens objektum létrehozása
  */
  final function create(array $params=null){
    //Csak vak pélányon futhat.
    if (isset($this->id)) return;
  
    //1. objektum bejegyzése a fő objektum táblába
     $class = get_class($this);
     $sql = sprintf("INSERT INTO %s VALUES ('','%s')", $this->objectTable, $class);
     $this->db->query($sql);
    
    //2. auto generált id lekérdezése, és beállítása $this->id -be
     //$sql = sprintf("SELECT max(id) as id FROM %s", $this->objectTable);
     //$result = $this->db->query($sql);
     //$this->id = $result[0]['id'];
     $this->id = $this->db->getLastInsertID();
    
    //3. objektum bejegyzése az osztályaihoz tartozó táblákba 
    $objectValues = array();

        if (!is_null($params)) {
        $params=array_reverse($params,true);
        $params['id'] = $this->id;
        $params=array_reverse($params,true);
       // $params['id'] = $this->id;
        foreach ($params as $key => $value) {
          if (is_array($value)) {
              $objectValues[$key] = $value;
              unset($params[$key]);
            }
        }
         $attributes = array_keys($params);
         $values = array_values($params);
         $table = strtolower(get_class($this));
         $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", $table, implode(",", $attributes), implode("','", $values));
         $data = $this->db->query($sql);
        }
    
    //4. alosztályok létrehozási tevékenységének futtatása
    $this->onAfterCreate($params);
  }
  
  /**
  Attribútumok lekérdezése
  
  $field_names=array(mezőnév,mezőnév, ...)
  
  return array(mezőnév=>érték, mezőnév=>érték, ...)
  Ha $field_names üres, akkor adjon vissza minden mezőt.
  */
  final protected function getFields(array $field_names=null){
    //megadott mezők lekérdezése a megfelelő táblákból

    if (isset($field_names))
      $sql = sprintf("SELECT %s FROM %s WHERE id = %s", implode(',', $field_names), strtolower(get_class($this)), $this->id);
    else {
      $sql = sprintf("SELECT * FROM %s WHERE id  = %s", strtolower(get_class($this)), $this->id);
    }
    $result = $this->db->query($sql);
    if (!isset($result)) {
          throw new Exception("Database Error [{$this->db->errno}] {$this->db->error}");
    }
    //return $result->fetch_assoc();
    return $result[0];
  }
  
  /**
  Attribútumok beállítása

  $field_values=array(mezőnév=>érték, mezőnév=>érték, ...)
  */
  final protected function setFields(array $field_values){
    //megadott mezők beállítása a megfelelő táblákba
    $s = array();
    foreach($field_values as $key => $value){
      $s[] = $key."='".$value."'";
    }
    $sql = sprintf("UPDATE %s SET %s WHERE id = %s", strtolower(get_class($this)), implode(", ", $s) , $this->id);
    $result = $this->db->query($sql);
    return $result;
  }

  final function delete(){
    //objektum törlése a megfelelő táblákból  
    $sql = array();
    $sql[] = sprintf("DELETE FROM %s WHERE id  = %s", strtolower(get_class($this)), $this->id);
    $sql[] = sprintf("DELETE FROM %s WHERE id = %s", $this->objectTable, $this->id);
    $result = $this->db->query($sql);
    return $result;
  }
  
  /**
  return hiba kódok array
  
  Létrehozási/módosítási paraméterek ellenőrzése
  Alosztály implementálja  
  */
  abstract function validate(array $params=null);
  
  /**
  return void
  
  Tetszőleges létrehozási tevékenység. 
  Alosztály implementálja  
  */
  abstract protected function onAfterCreate(array $params=null);      
  
}
