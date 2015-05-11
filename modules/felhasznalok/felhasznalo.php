<?php

class Felhasznalo extends Persistent{

 protected $azon;
 protected $jelszo;
 protected $email;

 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */
  public function validate(array $params=null){
      $errors = array();
      if(empty($params['azon']))
          $errors[]=array(Error::MANDATORY, "azon");
      if(empty($params['email']))
          $errors[]= array(Error::MANDATORY, "email");

      $allFields = $this->validateFields($params);
      return array_merge($errors, $allFields);
  }
  
  /**
  return void
  
  Tetsz�leges l�trehoz�si tev�kenys�g. 
  Aloszt�ly implement�lja  
  */
  protected function onAfterCreate(array $params=null){
    $this->azon=$params['azon'];
    $this->jelszo=$params['jelszo'];
    $this->jelszo=$params['email'];
  }
  
  //TODO: getterek, setterek a Persistent-ben l�v� getFields �s setFields seg�ts�g�vel
    public function getFelhasznaloFields(){
      return $this->getFields();
    }
    
    public function setFelhasznaloFields(array $values){
       $errors = $this->validateFields($values);
       if(count($errors) > 0){
            return $errors;
        }
        $values['jelszo'] = md5($values['jelszo']);
        return $this->setFields($values);

    }     
    
    protected function onBeforeDelete(array $params=null) {}

    function validateFields(array $params = null)
    {
        $errors = array();
        foreach($params as $key => $value) {
            if (empty($value)) {
                $errors[] = array(Error::EMPTY_FIELD, $key);
                continue;
            }
            if($key == "jelszo"){
                if(strlen($value) < 5 )
                    $errors[] = array(Error::SHORT_PASSWORD, $key);
            }
            if ($key == "email") {
                if (strpos($value, '@') == false)
                    $errors[] = array(Error::NOT_EMAIL, $key);
            }
        }
        return $errors;

    }

    public function to_string(){
        return implode(", ", $this->getFelhasznaloFields());
    }

    protected function onBeforeCreate(array $params=null){
        if(isset($params['jelszo'])){
            $params['jelszo'] = md5($params['jelszo']);
        }

        return $params;
    }


}
?>