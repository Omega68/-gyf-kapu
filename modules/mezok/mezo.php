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
       /*  if(empty($params['azon']))
             $errors[]=array(Error::MANDATORY, "azon");*/
      if(empty($params['nev']) && !isset($params['nev']))
          $errors[]=array(Error::MANDATORY, "nev");
          if(empty($params['tipus']) && !isset($param['tipus']))
              $errors[]=array(Error::MANDATORY, "tipus");
          if(empty($params['kotelezoseg']))
              $errors[]=array(Error::MANDATORY, "kotelezoseg");
          if(empty($params['sablon_azon']))
              $errors[]=array(Error::MANDATORY, "sablon_azon");

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
    public function getMezoFields(){
      return $this->getFields();
    }
    
    public function setMezoFields(array $values){
      return $this->setFields($values);
    }     
    
    public static function getMezokUrlaphoz($param) {
      $s = array();
      $s[] = 'id';
    //  return Persistent::getSelectFields($s,'Mezo','sablon_azon',$param);
    }
    
    protected function onBeforeDelete(array $params=null) {}
}

  ?>