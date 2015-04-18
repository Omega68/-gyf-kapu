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
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */
  public function validate(array $params=null){
  }
  
  /**
  return void
  
  Tetsz�leges l�trehoz�si tev�kenys�g. 
  Aloszt�ly implement�lja  
  */
  protected function onAfterCreate(array $params=null){
    $this->azon=$params['azon'];
    $this->jelszo=$params['jelszo'];
  }
  
  //TODO: getterek, setterek a Persistent-ben l�v� getFields �s setFields seg�ts�g�vel
    public function getFelhasznaloFields(){
      return $this->getFields();
    }
    
    public function setFelhasznaloFields(array $values){
      return $this->setFields($values);
    }     
}
?>