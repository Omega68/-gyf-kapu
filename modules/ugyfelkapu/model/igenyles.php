<?php

require_once("../persistent.php");

class Igenyles extends Persistent{
 
  //protected static function getTableName() {
  //       return 'igenyles';
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
          if(empty($params['statusz']))
         $errors[]='Nincs statusz megadva';
          if(empty($params['utolso_modositas']))
         $errors[]='Nincs utolso_modositas megadva';
          if(empty($params['sablon_azon']))
         $errors[]='Nincs sablon_azon megadva';
          if(empty($params['ugyfel_azon']))
         $errors[]='Nincs ugyfel_azon megadva';
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
    public function getIgenylesFields(){
      return $this->getFields();
    }
    
    public function setIgenylesFields(array $values){
      return $this->setFields($values);
    }     
    
    protected function onBeforeDelete(array $params=null) {}  
}