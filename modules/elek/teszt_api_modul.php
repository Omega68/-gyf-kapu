<?

class Teszt_API_Modul extends API_Module{

  function getSupportedFunctions(){
    return array('getTesztAdatok');
  }

  function handleRequest($function,array $params, array $data=null){  
    if ($function==='getTesztAdatok'){     
      $adatok=array('nev'=>'Elek','cim'=>'Debrecen','telefon'=>'0612345678');
      $json= json_encode($adatok);
      
      header('Content-Type: text/json', false, 200);
      echo $json;
    } 
  }  
}

