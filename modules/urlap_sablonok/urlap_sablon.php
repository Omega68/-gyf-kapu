<?php

class UrlapSablon extends Persistent{
 
  //protected static function getTableName() {
  //      return 'urlap_sablon';
  //}
 
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
          if(empty($params['allapot']))
              $errors[]=array(Error::MANDATORY, "allapot");
          if(empty($params['admin_azon']))
              $errors[]=array(Error::MANDATORY, "admin_azon");

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
    public function getUrlapSablonFields(){
      return $this->getFields();
    }
    
    public function setUrlapSablonFields(array $values){
      return $this->setFields($values);
    }  
    
    public function onBeforeDelete(array $params=null){
      $result=Mezo::getMezokUrlaphoz($this->getFields()[id]);
      foreach ($result as $act){
        $idk = implode(',',$act).'<br/>';
        $mezo1 = new Mezo($idk);
        $mezo1.delete();
      }
    }
    
    public function createMezo(array $values){
      $pm = PersistenceManager::getInstance(); 
      $sablon_adatok=array(
        'sablon_azon' => $this->getFields()[azon]
      );
      $mezo_adatok=$values+$sablon_adatok;
      
      return $pm->createObject('mezo',$mezo_adatok);
    }

   
}

  ?>