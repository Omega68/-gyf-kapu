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
         $errors[]='Nincs azon megadva';
          if(empty($params['letrehozas_datuma']))
         $errors[]='Nincs letrehozas_datuma megadva';
          if(empty($params['allapot']))
         $errors[]='Nincs allapot megadva';
          if(empty($params['admin_azon']))
         $errors[]='Nincs admin_azon megadva';
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