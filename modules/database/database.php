<?

class Database{
	protected $user, $password, $location, $dbname, $charset;
	protected $resource = null;

	static private $instance;

	static function getInstance() {
		if (!isset(self::$instance)) { 
			self::$instance = new self(DBConfig::$user, DBConfig::$password,
					DBConfig::$location, DBConfig::$dbname, DBConfig::$charset);
			self::$instance->connect();
		}

		return self::$instance;      
	}

	function __construct($user, $password, $location, $dbname, $charset) {
		$this->user = $user;
		$this->password = $password;
		$this->location = $location;
		$this->dbname = $dbname;
		$this->charset = $charset;
	}

	final function connect(){
		if (isset($this->resource)) return false;

		//csatlakozáskor visszakapok egy resource típust a mysql kapcsolatra, ezt megőrizzük
		if (!($this->resource = mysqli_connect($this->location, $this->user, $this->password,$this->dbname))) {
			throw new Exception("Nem tudtam csatlakozni az adatbázishoz!");
		}
		mysqli_set_charset($this->resource,$this->charset);
    
		return true;
	}

  /**
  return: 
    - ha van visszatérő adat, akkor az eredmény sorai array-ben, auto inkrementált indexeléssel: array(0=>array(...), 1=>array(...) ) 
    - ha nincs visszatérő adat, akkor true
    - ha hiba van, akkor Exception    
  */
	final function query($sql) {
    $this->queries[]=$sql;
    
    $res = mysqli_query($this->resource,$sql);
	  if ($res===false) throw new Exception(mysqli_error($this->resource));;
    if ($res===true) return true;
		  
		$result = array();
		while (($row = mysqli_fetch_assoc($res)) !== null) {
			$result[] = $row;
		}

		mysqli_free_result($res);
		return $result;
	}
	
	final function getLastInsertID(){
    return mysqli_insert_id($this->resource);  	   
  }
	
	final function getEscaped($str){
    return mysqli_real_escape_string($this->resource,$str);
  } 
	
}




