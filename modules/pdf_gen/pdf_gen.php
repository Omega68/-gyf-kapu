<?

include 'mpdf/mpdf.php';

class PDF_Gen{
  private $azon;
  
  function __construct($azon) {
        $this->azon = $azon;
    }
    
  private function generateHTML()  
    {
            $pm = PersistenceManager::getInstance();
            $igenylesadat = $pm->getObjectsByField('Igenyles',['azon' => $this->azon]);
            $kigenylesadat = $igenylesadat[0]->getIgenylesFields();
            
            $azon = $kigenylesadat[ugyfel_azon];
            $ugyfel = $pm->getObjectsByField('Ugyfel',['azon' => $azon]);
            $kugyfel = $ugyfel[0]->getUgyfelFields();

            $fejlec = '<h1 align="center">Igénylés</h1>           
            <br/>
            <br/>';
            
            $uadatok = 'Az gringó adatai:
            <table border="1" cellspacing="0">
                        <tr>
                            <th>Azonosító:</th>
                            <th>'.$kugyfel[azon].'</th>
                        </tr>
                        <tr>
                            <th>Cím:</th>
                            <th>'.$kugyfel[cim].'</th>
                        </tr>
                        <tr>
                            <th>E-mail:</th>
                            <th>'.$kugyfel[email].'</th>
                        </tr>
                        <tr>
                            <th>Telefon:</th>
                            <th>'.$kugyfel[telefon].'</th>
                        </tr>
            </table>
            <br/>
            <br/>';
            
            $uigenyles = 'Igénylés:
            <table border="1" cellspacing="0">
                        <tr>
                            <th>Azonosító</th>
                            <th>'.$kigenylesadat[azon].'</th>
                        </tr>
                        <tr>
                            <th>Létrehozás dátuma</th>
                            <th>'.$kigenylesadat[letrehozas_datuma].'</th>
                        </tr>
                        <tr>
                            <th>Utolsó módosítás dátuma</th>
                            <th>'.$kigenylesadat[utolso_modositas].'</th>
                        </tr>
                        <tr>
                            <th>Állapot</th>
                            <th>'.$kigenylesadat[statusz].'</th>
                        </tr>
            </table>
            <br/>';
            
            $imezok = 'Részletek:
            <table border="1" cellspacing="0">';

            $kmezok=$pm->getObjectsByField('KitoltottMezo',['igenyles_azon' => $kigenylesadat[azon]]);
            foreach($kmezok as $km){
                $temp=$pm->getObjectsByField('Mezo',['azon' => $km->getKitoltottMezoFields()[mezo_azon]]);
                $data2[]=[$temp[0]->getMezoFields()[tipus] => $km->getKitoltottMezoFields()[tartalom]];
                $imezok = $imezok.'<tr>
                            <th>'.$temp[0]->getMezoFields()[tipus].'</th>
                            <th>'.$km->getKitoltottMezoFields()[tartalom].'</th>
                           </tr>';
             }
             
            $imezok = $imezok.'</table>';
            
            return $fejlec.$uadatok.$uigenyles.$imezok;
    }
      
    public function createPDF()            
    {
      $mpdf = new mPDF();
      $mpdf->WriteHTML($this->generateHTML());
      $tmp = $mpdf->Output('igenyles-'.$this->azon.'.pdf','D');
      exit;  
      return $tmp;     
    }

}

