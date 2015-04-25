<?php

class KivitelezesiTerv extends Persistent{
 
  //protected static function getTableName() {
  //      return 'kivitelezesi_terv';
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
          if(empty($params['letrehozo']))
         $errors[]='Nincs letrehozo megadva';
          if(empty($params['datum']))
         $errors[]='Nincs datum megadva';
          if(empty($params['igenyles_azon']))
         $errors[]='Nincs igenyles_azon megadva';
          if(empty($params['dokumentum']))
         $errors[]='Nincs dokumentum megadva';
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
    public function getKivitelezesiTervFields(){
      return $this->getFields();
    }
    
    public function setKivitelezesiTervFields(array $values){
      return $this->setFields($values);
    }
    
    protected function onBeforeDelete(array $params=null) {}       
}

  ?>