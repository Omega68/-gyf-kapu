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
      echo $result->num_rows;
    if(count($result) == 1){
      $row = $result[0];
        return new $row['class']($row['id']);
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
       // $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        if($class!='Admin' && $class!='Ugyfel')
        {
            $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        }else{
            $sql=sprintf("SELECT * FROM %s s inner join felhasznalo f on s.id=f.id WHERE", strtolower($class));
        }
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
    
    public function getObjectsByFieldOr($class, $params=null){
       // $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        if($class!='Admin' && $class!='Ugyfel')
        {
            $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        }else{
            $sql=sprintf("SELECT * FROM %s s inner join felhasznalo f on s.id=f.id WHERE", strtolower($class));
        }
        $counter=0;
        foreach($params as $key=>$value) {
            $sql .= " " . strtolower($key) . " LIKE " . " '%".strtolower($value)."%'";
            $counter++;
            if($counter<count($params))
                $sql.=" or";

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
    }

    public function updateObjectByFields($class, $params=null, $where = null){
        $sql = sprintf("UPDATE %s SET", strtolower($class));
        $counter=0;
        foreach($params as $key=>$value) {
            $sql .= " " . $key . " = " . " '".$value."'";
            $counter++;
            if($counter<count($params))
                $sql.=" , ";

        }
        $counter=0;

        if(!empty($where)){
            $sql .= " where ";
            foreach($where as $key=>$value) {
                $sql .= " " . $key . " = " . " '".$value."'";

                $counter++;
                if($counter<count($where))
                    $sql.=" and";
            }
        }

       // echo $sql;
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
     * Általános lekérdező metódus, mely nem szűr paraméterekre
     *
     * @param $class Annak az osztálynak a neve, ahonnan lekérdezünk
     * @param null $limit Mennyit
     * @param null $offset Honnan
     * @param bool $order legyen rendezve? (default: false)
     * @param bool $isDesc csökkenő? (default: false)
     *
     * @return visszatérési érték maguk az object-ek
     */
    public function getObjectsByLimitOffsetOrderBy($class,$limit=null,$offset=null,$order=null,$isDesc=false){
      //  $sql = sprintf("SELECT * FROM %s ", strtolower($class));
        if($class!='Admin' && $class!='Ugyfel')
        {
            $sql = sprintf("SELECT * FROM %s", strtolower($class));
        }else{
            $sql=sprintf("SELECT * FROM %s s inner join felhasznalo f on s.id=f.id ", strtolower($class));
        }
        $counter=0;
        if($order!=null){
            $sql.=" ORDER BY ".$order;
            if($isDesc==true)
                $sql.=" DESC";
            else
                $sql.=" ASC";
        }
        if($limit!=null) {
            $sql .= " LIMIT " . $limit;
            if($offset!=null)
                $sql.=" OFFSET ".$offset;
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
    }

    /*
    * return a megadott táblával, paraméterekkel rendelkező objects, limit a menyiség, és offset a start rész,
    * order azt jelöli ami szerint rendezni kell, default értékben növekvő, true érték esetén csökkenő lesz
    */
    public function getObjectsByFieldLimitOffsetOrderBy($class, $params=null, $limit=null,$offset=null, $order=null, $isDesc=false){
        $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        $counter=0;
        foreach($params as $key=>$value) {
            $sql .= " " . $key . " = " . " '".$value."'";
            $counter++;
            if($counter<count($params))
                $sql.=" and";

        }
        if($order!=null){
            $sql.=" ORDER BY ".$order;
            if($isDesc==true)
                $sql.=" DESC";
            else
                $sql.=" ASC";
        }
        if($limit!=null) {
            $sql .= " LIMIT " . $limit;
            if($offset!=null)
                $sql.=" OFFSET ".$offset;
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
	}
  
    public function getObjectsByFieldLimitOffsetOrderByOr($class, $params=null, $limit=null,$offset=null, $order=null, $isDesc=false){
        if($class!='Admin' && $class!='Ugyfel')
        {
            $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        }else{
            $sql=sprintf("SELECT * FROM %s s inner join felhasznalo f on s.id=f.id WHERE", strtolower($class));
        }

        $counter=0;
        foreach($params as $key=>$value) {
            $sql .= " " . strtolower($key) . " LIKE " . " '%".strtolower($value)."%'";
            $counter++;
            if($counter<count($params))
                $sql.=" or";

        }
        if($order!=null){
            $sql.=" ORDER BY ".$order;
            if($isDesc==true)
                $sql.=" DESC";
            else
                $sql.=" ASC";
        }
        if($limit!=null) {
            $sql .= " LIMIT " . $limit;
            if($offset!=null)
                $sql.=" OFFSET ".$offset;
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
	}


    /**
     * Egy metódus, mely kisebb, vagy nagyobb összehasonlításokat végzi
     *
     * @param $class az osztály, ahonnan le kívánunk kérdezni
     * @param $param az osztály azon attribútuma, amivel az összehasonlítást végezzük
     * @param $value az érték, aminél nagyobb attribútumú object-ek lesznek kiválasztva
     * @param $limit a lekérdezendő elemek száma
     * @param $offset honnan kezdje a lekérdezést
     * @param $bigger default értékben false így kisebb elemmekkel tér vissza
     * @param $isDesc alap értékben false, true értékben növekvő lesz
     * @param $order rendezzen-e az elemek szerint
     *
     * return object-ek melyek egy adott feltételnek megfelelnek
     */
    public function getObjectsBiggerOrLess($class,$param,$value,$limit=null,$offset=null, $bigger=false,$order=null, $isDesc=false){
        $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
       /* if($class!='Admin' && $class!='Ugyfel')
        {
            $sql = sprintf("SELECT * FROM %s WHERE", strtolower($class));
        }else{
            $sql=sprintf("SELECT * FROM %s s inner join felhasznalo f on s.id=f.id WHERE", strtolower($class));
        }*/
        $counter=0;
        //param-nak oszlop névnek kell lennie
        if($bigger)
            $sql .= " " . $param . " > " . " '".$value."'";
        else
            $sql.=" ".$param." < "." '".$value."'";
        if($order!=null){
                $sql.=" ORDER BY ".$order;
            if($isDesc==true)
                $sql.=" DESC";
            else
                $sql.=" ASC";
        }
        if($limit!=null) {
            $sql .= " LIMIT " . $limit;
            if($offset!=null)
                $sql.=" OFFSET ".$offset;
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
    }

    public function getAllObjects($class){
        $sql = sprintf("SELECT * FROM %s", strtolower($class));
        $result = $this->dbConnection->query($sql);
        $objects=array();
        $count=0;
        foreach($result as $key => $value){
            if(!empty($value)){
            $ojb=new $class($result[$count]['id']);
            $objects[]=$ojb;
            if($count<count($result))
                $count++;
            }
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
        unset($errors);
      return $object;
    } else {
        if($this->validationError($errors))
            return;
        return null;
    }


  }

    private function validationError($errors){

        if(count($errors) > 0 ){
            echo "validation error: ";
            foreach( $errors as $e )
                echo Error::get_error_msg($e[0]) . " Mező: " . $e[1] . "<br/>";
            return true;
        }

        return false;

    }
  
  /**
  return table name string
  */
  final function getTableNameForClass($classname){
    return $classname::getTableName();
  }
  
}
