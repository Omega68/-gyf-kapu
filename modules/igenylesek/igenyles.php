<?php

class Igenyles extends Persistent{
 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
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
  
  Tetsz�leges l�trehoz�si tev�kenys�g. 
  Aloszt�ly implement�lja  
  */
  protected function onAfterCreate(array $params=null){
  }
  
  //TODO: getterek, setterek a Persistent-ben l�v� getFields �s setFields seg�ts�g�vel
    public function getIgenylesFields(){
      return $this->getFields();
    }
    
    public function setIgenylesFields(array $values){
      return $this->setFields($values);
    }     
    
    protected function onBeforeDelete(array $params=null) {}  
}