<?php

class KitoltottMezo extends Persistent{
 
  //protected static function getTableName() {
  //      return 'kitoltott_mezo';
  //}
 
 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */
  public function validate(array $params=null){
  $errors = array();
        /* if(empty($params['azon']))
             $errors[]=array(Error::MANDATORY, "azon");*/
         if(empty($params['tartalom']))
             $errors[]=array(Error::MANDATORY, "tartalom");
        if(empty($params['mezo_azon']))
            $errors[]=array(Error::MANDATORY, "mezo_azon");
        if(empty($params['igenyles_azon']))
            $errors[]=array(Error::MANDATORY, "igenyles_azon");
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
    public function getKitoltottMezoFields(){
      return $this->getFields();
    }
    
    public function setKitoltottMezoFields(array $values){
      return $this->setFields($values);
    }     
    
    protected function onBeforeDelete(array $params=null) {}  
}

?>