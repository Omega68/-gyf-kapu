<?php


class Ugyfel extends Felhasznalo
{
    private $cim;
    private $email;
    private $telefon;

    protected function onAfterCreate(array $params = null) {
      $this->azon = $params['azon'];
      $this->cim=$params['cim'];
      $this->email=$params['email'];
      $this->telefon=$params['telefon'];
    }


    public function validate(array $params = null) {
        $errors = array();
         if(empty($params['azon']))
         $errors[]=array(Error::MANDATORY, "azon");
         if(empty($params['cim']))
         $errors[]= array(Error::MANDATORY, "cim");
         if(empty($params['email']))
         $errors[]= array(Error::MANDATORY, "email");
         if(empty($params['telefon']))
         $errors[]= array(Error::MANDATORY, "telefon");
        $allFields = $this->validateFields($params);
        return array_merge($errors, $allFields);
    }

    public function validateFields(array $params = null){
        $errors = array();
        foreach($params as $key => $value) {
            if (empty($value)) {
                $errors[] = array(Error::EMPTY_FIELD, $key);
                continue;
            }
            if ($key == "azon") {
                if (!is_numeric($value))
                    $errors[] = array(Error::NOT_NUMERIC, $key);
            }
            if ($key == "email") {
                if (strpos($value, '@') == false)
                    $errors[] = array(Error::NOT_EMAIL, $key);
            }
            if($key == "jelszo"){
                if(strlen($value) < 5 )
                    $errors[] = array(Error::SHORT_PASSWORD, $key);
            }
        }
        return $errors;
    }

    //TODO: getterek, setterek a Persistent-ben lévő getFields és setFields segítségével
    public function getUgyfelFields(){
      return $this->getFields();
    }
    
    public function setUgyfelFields(array $values){
      return $this->setFields($values);
    }

    public function to_string(){
        return implode(", ", $this->getUgyfelFields());
    }
    
    protected function onBeforeDelete(array $params=null) {}
}

