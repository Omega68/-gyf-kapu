<?php

class KivitelezesiTerv extends Persistent{

    protected $ugyfel_azon;
    protected $path;

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