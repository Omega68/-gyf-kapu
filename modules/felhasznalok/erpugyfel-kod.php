<?php

class ERPUgyfelKod extends Persistent{

 protected $azon;
 protected $kod;

 /**
  return hiba k�dok array
  
  L�trehoz�si/m�dos�t�si param�terek ellen�rz�se
  Aloszt�ly implement�lja  
  */
  public function validate(array $params=null){
      $errors = array();
      if(empty($params['azon']))
          $errors[]=array(Error::MANDATORY, "azon");
      if(empty($params['kod']))
          $errors[]=array(Error::MANDATORY, "kod");

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
        $errors = array();
        foreach($params as $key => $value) {
            if (empty($value)) {
                $errors[] = array(Error::EMPTY_FIELD, $key);
                continue;
            }
        }
        return $errors;

    }

    public function getKodFields(){
        return $this->getFields();
    }

    public function setKodFields(array $values){
        return $this->setFields($values);
    }

    public function to_string(){
        return implode(", ", $this->getUgyfelFields());
    }

}
?>