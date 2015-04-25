<?php

class Mezo extends Persistent{
 
  //protected static function getTableName() {
  //      return 'mezo';
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
          if(empty($params['tipus']))
         $errors[]='Nincs tipus megadva';
          if(empty($params['kotelezoseg']))
         $errors[]='Nincs kotelezoseg megadva';
          if(empty($params['sablon_azon']))
         $errors[]='Nincs sablon_azon megadva';
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
    public function getMezoFields(){
      return $this->getFields();
    }
    
    public function setMezoFields(array $values){
      return $this->setFields($values);
    }     
    
    public static function getMezokUrlaphoz($param) {
      $s = array();
      $s[] = 'id';
      return Persistent::getSelectFields($s,'Mezo','sablon_azon',$param);    
    }
    
    protected function onBeforeDelete(array $params=null) {}  
}

  ?>