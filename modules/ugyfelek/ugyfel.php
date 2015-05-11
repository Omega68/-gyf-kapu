<?php


class Ugyfel extends Felhasznalo
{
    private $cim;
    private $telefon;

    protected function onAfterCreate(array $params = null) {
      $this->cim=$params['cim'];
      $this->telefon=$params['telefon'];
    }


    public function validate(array $params = null) {
        $errors = array();
         if(empty($params['azon']))
         $errors[]=array(Error::MANDATORY, "azon");
         if(empty($params['cim']))
         $errors[]= array(Error::MANDATORY, "cim");
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

        }
        return $errors;
    }

    //TODO: getterek, setterek a Persistent-ben lévő getFields és setFields segítségével
    public function getUgyfelFields(){
        //$arr=array_merge($this->getFields(),$this->getFelhasznaloFields());
        $arr=$this->getFields();
        //var_dump($arr);
        return $arr;
    }
    
    public function setUgyfelFields(array $values){
      return $this->setFields($values);
    }
    
    protected function onBeforeDelete(array $params=null) {}

    protected function onBeforeCreate(array $params=null){
        return parent::onBeforeCreate($params);

    }

}

