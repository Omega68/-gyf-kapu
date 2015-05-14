<?php


class ERP_API extends API_Module
{

    /**
     * return array of string
     * Ebben kell a keretrendszer felé megadni, hogy milyen nevű api hívásokat támogat a modul.
     * A függvény nevét a ?module=függvényneve url paraméterben kell megadni.
     */
    function getSupportedFunctions()
    {
        return [
            'getIgenyles',
            'setIgenyles'            
        ];
    }

    /**
     * $function=$_GET['function']
     * $params=$_GET
     * $data=$_POST
     *
     * A http választ a kimenetre kell írni, és headert beállítani
     * A $function elfogadott értékeit a getSupportedFunctions metódusban kell visszaadni.
     */
    function handleRequest($function, array $params, array $data = null)
    {
        $pm = PersistenceManager::getInstance();

        if ($function=='getIgenyles') {
            $data=array();
            $igenylesek=$pm->getAllObjects("Igenyles");
            foreach($igenylesek as $i){
              $t=$i->getIgenylesFields();
              
              $data2=array();
              $kmezok=$pm->getObjectsByField('KitoltottMezo',['igenyles_azon' => $t[azon]]);
              foreach($kmezok as $km){
                $temp=$pm->getObjectsByField('Mezo',['azon' => $km->getKitoltottMezoFields()[mezo_azon]]);
                $data2[]=[$temp[0]->getMezoFields()[tipus] => $km->getKitoltottMezoFields()[tartalom]];
              }
              $t+=$data2;
              $data2=null;
              $data[]=$t;            
            }
                        
            echo json_encode($data);
        }
        
        if ($function=='setIgenyles') {
          if ((empty($params['id'])) || (empty($params['statusz']))) {
              echo json_encode(['msg' => 'Nem megfelelő paraméterek!']);
              return;
          }   
          $uf = new Igenyles($params['id']);
          $uf->setIgenylesFields(['statusz' => $params['statusz']]);          
        }
    }
}