<?

require_once("../database.php");

class PersistenceManager{

 const OBJECT_TABLE_NAME = "object";

  private $dbconnection;
  private $objectTable;

  private static $instance;
  
  static function getInstance(){  
    if (!isset(self::$instance)) self::$instance=new self(DatabaseConnection::getInstance());
    return self::$instance;
  }
  
  final function __construct(DatabaseConnection $connection){
   $this->objectTable = PersistenceManager::OBJECT_TABLE_NAME;
    $this->dbconnection = $connection;    
  }
  
  /**  
  return object
  */
  final function getObject($id){
    $sql = sprintf("SELECT * FROM %s WHERE id = %s", $this->mainObjectTableName, $id);
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
