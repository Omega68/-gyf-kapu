<?


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
  
  final function getTableColumn($param){
    $fields = array();
    $sql = sprintf("SHOW COLUMNS FROM %s", $param);
    $res = $this->db->query($sql);
    foreach ($res as $t){
      $fields[] = explode(',',implode(', ',$t))[0];
    }
    return $fields;
  }
  
  /**
  Perzisztens objektum létrehozása
  */
  final function create(array $params=null){
    //Csak vak pélányon futhat.
    if (isset($this->id)) return;

      /*if(count($this->validate($params))  > 0){
          return;
      }*/
        $errors = $this->validate($params);
      if($this->validationError($errors))
          return $errors;


      //1. objektum bejegyzése a fő objektum táblába
     $class = get_class($this);
     $sql = sprintf("INSERT INTO %s VALUES ('','%s')", $this->objectTable, $class);
     $this->db->query($sql);

    
    //2. auto generált id lekérdezése, és beállítása $this->id -be
     $this->id = $this->db->getLastInsertID();
    //3. objektum bejegyzése az osztályaihoz tartozó táblákba
    $objectValues = array();

        if (!is_null($params)) {
        $params=array_reverse($params,true);
        $params['id'] = $this->id;
        $params=array_reverse($params,true);
       // $params['id'] = $this->id;
        /*foreach ($params as $key => $value) {
          if (is_array($value)) {
              $objectValues[$key] = $value;
              unset($params[$key]);
            }
        }
         */

        //Szülőosztályok bejárása
        $actual = get_class($this);
        $classes = array();
        $classes[] = $actual;
        while (strcmp(get_parent_class($actual),'Persistent')!=0) {
          $actual = get_parent_class($actual);
          $classes[] = $actual;
        }
        $classes = array_reverse($classes,true);
        
        foreach ($classes as $akt){
          $fields = array();
          $cols = Persistent::getTableColumn(strtolower($akt));
          foreach ($cols as $ident){
            $fields[$ident]=$params[$ident];
          }
            $fields = $this->onBeforeCreate($fields);
            $attributes = array_keys($fields);
            $values = array_values($fields);

            $sql = sprintf("INSERT INTO %s (%s) VALUES ('%s')", strtolower($akt), implode(",", $attributes), implode("','", $values));
            //echo $sql;
          $data = $this->db->query($sql);
          }
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
    $actual = get_class($this);
    $classes = array();
    $classes[] = $actual;
    while (strcmp(get_parent_class($actual),'Persistent')!=0) {
          $actual = get_parent_class($actual);
          $classes[] = $actual;
    }
    $table = strtolower($classes[0]);
    for($i=1; $i<count($classes); $i++)
    $table = $table.sprintf(" LEFT JOIN %s USING(id)", strtolower($classes[$i]));


    if (isset($field_names))
      $sql = sprintf("SELECT %s FROM %s WHERE id = %s", implode(',', $field_names), strtolower(get_class($this)), $this->id);
    else {
      $sql = sprintf("SELECT * FROM %s WHERE id  = %s", strtolower($table), $this->id);

        //$sql = sprintf("SELECT * FROM %s WHERE id  = %s", $table, $this->id);
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
      $errors = $this->validateFields($field_values);
      if($this->validationError($errors))
          return $errors;

      $actual = get_class($this);
      $classes = array();
      $classes[] = $actual;
      while (strcmp(get_parent_class($actual),'Persistent')!=0) {
          $actual = get_parent_class($actual);
          $classes[] = $actual;
      }
      $table = strtolower($classes[0]);
      for($i=1; $i<count($classes); $i++)
          $table = $table.sprintf(" INNER JOIN %s USING(id)", strtolower($classes[$i]));


      $sql = sprintf("UPDATE %s SET %s WHERE id = %s", $table, implode(", ", $s) , $this->id);
    //echo $sql;
      $result = $this->db->query($sql);
    return $result;
  }

  final function delete(){
    //objektum törlése a megfelelő táblákból  
    
    $this->OnBeforeDelete();
    
    $sql = array();
    $actual = get_class($this);
    $classes = array();
    $classes[] = $actual;
    while (strcmp(get_parent_class($actual),'Persistent')!=0) {
          $actual = get_parent_class($actual);
          $classes[] = $actual;
    }
    
    foreach ($classes as $akt){
      $sql = sprintf("DELETE FROM %s WHERE id = %s", strtolower($akt), $this->id);  
      $result[] = $this->db->query($sql);
      }
    //$sql[] = sprintf("DELETE FROM %s WHERE id = %s", $this->objectTable, $this->id);
    $result[] = $this->db->query($sql);
    return $result;
  }
  
  /**
  return hiba kódok array
  
  Létrehozási/módosítási paraméterek ellenőrzése
  Alosztály implementálja  
  */
  abstract function validate(array $params=null);/*
  abstract function validateFields(array $params=null);*/


    /**
  return void
  
  Tetszőleges létrehozási tevékenység. 
  Alosztály implementálja  
  */
  abstract protected function onAfterCreate(array $params=null);
  abstract protected function onBeforeDelete(array $params=null);      

  private function validationError($errors){

        if(count($errors) > 0 ){
            return true;
        }
        return false;

    }

    protected function onBeforeCreate(array $params=null){
        return $params;
    }

}
