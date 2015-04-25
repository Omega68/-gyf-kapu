<?php


class Ugyfel extends Persistent
{
    const TABLE_NAME = "ugyfel";
    private $azon;
    private $cim;
    private $email;
    private $telefon;
    
    
    //protected function getTableName() {
    //    return "ugyfel";
    //}
    

    protected function onAfterCreate(array $params = null) {
      $this->azon = $params['azon'];
      $this->cim=$params['cim'];
      $this->email=$params['email'];
      $this->telefon=$params['telefon'];
      //TODO valami ilyesmi...
    }


    public function validate(array $params = null) {
        $errors = array();
         if(empty($params['azon']))
         $errors[]='Nincs azon megadva';
         if(empty($params['cim']))
         $errors[]='Nincs cim megadva';
         if(empty($params['email']))
         $errors[]='Nincs email megadva';
         if(empty($params['telefon']))
         $errors[]='Nincs telefon megadva';
        return $errors;
    }
    
    //TODO: getterek, setterek a Persistent-ben lévő getFields és setFields segítségével
    public function getUgyfelFields(){
      return $this->getFields();
    }
    
    public function setUgyfelFields(array $values){
      return $this->setFields($values);
    }
    
    protected function onBeforeDelete(array $params=null) {}
}

