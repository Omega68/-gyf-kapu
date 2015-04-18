<?php
require_once("../persistent.php");

class Felhasznalo extends Persistent{
 const TABLE_NAME="felhasznalo";
 
 private $azon;
 private $jelszo;
 
  //protected static function getTableName() {
  //      return 'felhasznalo';
  //}
 
 /**
  return hiba kódok array
  
  Létrehozási/módosítási paraméterek ellenõrzése
  Alosztály implementálja  
  */
  public function validate(array $params=null){
  }
  
  /**
  return void
  
  Tetszõleges létrehozási tevékenység. 
  Alosztály implementálja  
  */
  protected function onAfterCreate(array $params=null){
    $this->azon=$params['azon'];
    $this->jelszo=$params['jelszo'];
  }
  
  //TODO: getterek, setterek a Persistent-ben lévõ getFields és setFields segítségével
    public function getFelhasznaloFields(){
      return $this->getFields();
    }
    
    public function setFelhasznaloFields(array $values){
      return $this->setFields($values);
    }     
}
?>