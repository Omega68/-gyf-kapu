<?php
require_once("../persistent.php");

class KitoltottMezo extends Persistent{
 
  //protected static function getTableName() {
  //      return 'kitoltott_mezo';
  //}
 
 /**
  return hiba kódok array
  
  Létrehozási/módosítási paraméterek ellenõrzése
  Alosztály implementálja  
  */
  public function validate(array $params=null){
  $errors = array();
         if(empty($params['azon']))
         $errors[]='Nincs azon megadva';
         if(empty($params['tartalom']))
         $errors[]='Nincs tartalom megadva';
        if(empty($params['mezo_azon']))
         $errors[]='Nincs mezo_azon megadva';
        if(empty($params['igenyles_azon']))
         $errors[]='Nincs igenyles_azon megadva';
  return $errors;
  }
  
  /**
  return void
  
  Tetszõleges létrehozási tevékenység. 
  Alosztály implementálja  
  */
  protected function onAfterCreate(array $params=null){
  }
  
  //TODO: getterek, setterek a Persistent-ben lévõ getFields és setFields segítségével
    public function getKitoltottMezoFields(){
      return $this->getFields();
    }
    
    public function setKitoltottMezoFields(array $values){
      return $this->setFields($values);
    }     
}

?>