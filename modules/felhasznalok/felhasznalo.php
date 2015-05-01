<?php

class Felhasznalo extends Persistent{

 protected $azon;
 protected $jelszo;

 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */
  public function validate(array $params=null){
      $errors = array();
      if(strlen($params['azon']) == 0 ){
          $errors[]=Error::EMPTY_FIELD;
      }
      return $errors;
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
    
    protected function onBeforeDelete(array $params=null) {}

    function validateFields(array $params = null)
    {
        // TODO: Implement validateFields() method.
    }

    public function to_string(){
        return implode(", ", $this->getFelhasznaloFields());
    }
}
?>