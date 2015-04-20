<?php 

require_once("../persistent.php");

class UrlapSablon extends Persistent{
 
  //protected static function getTableName() {
  //      return 'urlap_sablon';
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
          if(empty($params['letrehozas_datuma']))
         $errors[]='Nincs letrehozas_datuma megadva';
          if(empty($params['allapot']))
         $errors[]='Nincs allapot megadva';
          if(empty($params['admin_azon']))
         $errors[]='Nincs admin_azon megadva';
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
    public function getUrlapSablonFields(){
      return $this->getFields();
    }
    
    public function setUrlapSablonFields(array $values){
      return $this->setFields($values);
    }  
    
    public function createMezo(array $values){
      $pm = PersistenceManager::getInstance(); 
      $sablon_adatok=array(
        'sablon_azon' => $this->getFields()[azon]
      );
      $mezo_adatok=$values+$sablon_adatok;
      
      return $pm->createObject('mezo',$mezo_adatok);
    }

   
}

  ?>