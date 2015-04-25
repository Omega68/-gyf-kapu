<?php

class Admin extends Felhasznalo{
 
  //protected static function getTableName() {
  //      return 'admin';
  //}
 
 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */
  public function validate(array $params=null){
  $errors = array();
         if(empty($params['azon']))
         $errors[]='Nincs azon megadva';
  
  return $errors;
  }
  
  /**
  return void
  
  Tetsz�leges l�trehoz�si tev�kenys�g. 
  Aloszt�ly implement�lja  
  */
  protected function onAfterCreate(array $params=null){
  }
  
  //TODO: getterek, setterek a Persistent-ben l�v� getFields �s setFields seg�ts�g�vel
    public function getAdminFields(){
      return $this->getFields();
    }
    
    public function setAdminFields(array $values){
      return $this->setFields($values);
    } 
    
    protected function onBeforeDelete(array $params=null) {}      
}