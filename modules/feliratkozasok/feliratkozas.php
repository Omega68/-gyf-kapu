<?php

class Feliratkozas extends Persistent{
 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */

    private $igenyles_azon;
    private $email;

  public function validate(array $params=null){
     $errors = array();
      return $errors;
  }

    public function validateFields(array $params = null){
        $errors = array();
        foreach($params as $key => $value) {
            if (empty($value)) {
                $errors[] = array(Error::EMPTY_FIELD, $key);
                continue;
            }
        }
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
    public function getFeliratkozasFields(){
      return $this->getFields();
    }
    
    public function setFeliratkozasFields(array $values){
      return $this->setFields($values);
    }     
    
    protected function onBeforeDelete(array $params=null) {}  
}