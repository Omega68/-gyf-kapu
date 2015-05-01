<?


class PersistenceManager{

 const OBJECT_TABLE_NAME = "object";

  private $dbConnection;
  private $objectTable;

  private static $instance;
  
  static function getInstance(){  
    if (!isset(self::$instance)) self::$instance=new self(DatabaseConnection::getInstance());
    return self::$instance;
  }
  
  final function __construct(DatabaseConnection $connection){
   $this->objectTable = PersistenceManager::OBJECT_TABLE_NAME;
    $this->dbConnection = $connection;
  }
  
  /**  
  return object
  */
  final function getObject($id){
    $sql = sprintf("SELECT * FROM %s WHERE id = %s", $this->getMainObjectTableName(), $id);
    $result = $this->dbConnection->query($sql);
    if($result->num_rows == 1){
      $row = $result->fetch_assoc();
      return $row['class']($row['id']);                      
    }
    throw new Exception("Multiple rows affected.");
    
  }
  
  public function getMainObjectTableName(){
    return $this->objectTable;
  }


    /*
     * return a megadott táblával, és paraméterekkel rendelkező objects
     */
    public function getObjectsByField($class, $params=null){
        $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        $counter=0;
        foreach($params as $key=>$value) {
            $sql .= " " . $key . " = " . " '".$value."'";
            $counter++;
            if($counter<count($params))
                $sql.=" and";

        }
        $result = $this->dbConnection->query($sql);
        $count=0;
        $objects=array();
        foreach($result as $key => $value){
            $ojb=new $class($result[$count]['id']);
            $objects[]=$ojb;
            if($count<count($result))
                $count++;
        }
        return $objects;
       // throw new Exception("Multiple rows affected.");
    }

    public function getAllObjects($class){
        $sql = sprintf("SELECT * FROM %s", strtolower($class));
        $result = $this->dbConnection->query($sql);
        $count=0;
        $objects=array();
        foreach($result as $key => $value){
            $ojb=new $class($result[$count]['id']);
            $objects[]=$ojb;
            if($count<count($result))
                $count++;
        }
        return $objects;
    }
  
  /**
  return hiba kódok array
  */
  final function validateCreateObject($class,array $params=null){
    //vak példány létrehozása
    $object=new $class();
    return $object->validate($params);
  }
  
  /**  
  return object
  */
  final function createObject($class,array $params=null,array &$errors=null){
    //vak példány létrehozása
    $object=new $class();
    
    //validálás
    $errors=$object->validate($params);
    
    //Ha nem volt hiba, akkor létrehozzuk az objektumot, és visszaadjuk
    if (!$errors){
      $object->create($params);
      return $object;
    } else {
        return null;
    }
    
  }
  
  /**
  return table name string
  */
  final function getTableNameForClass($classname){
    return $classname::getTableName();
  }
  
}
