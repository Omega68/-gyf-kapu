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
             $errors[]=array(Error::MANDATORY, "azon");
          if(empty($params['letrehozas_datuma']))
              $errors[]=array(Error::MANDATORY, "letrehozas_datuma");
          if(empty($params['statusz']))
              $errors[]=array(Error::MANDATORY, "statusz");
          if(empty($params['utolso_modositas']))
              $errors[]=array(Error::MANDATORY, "utolso_modositas");
          if(empty($params['sablon_azon']))
              $errors[]=array(Error::MANDATORY, "sablon_azon");
          if(empty($params['ugyfel_azon']))
              $errors[]=array(Error::MANDATORY, "ugyfel_azon");

      $allFields = $this->validateFields($params);
      return array_merge($errors, $allFields);  }

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
    public function getIgenylesFields(){
      return $this->getFields();
    }
    
    public function setIgenylesFields(array $values){
      return $this->setFields($values);
    }     
    
    protected function onBeforeDelete(array $params=null) {}  
}