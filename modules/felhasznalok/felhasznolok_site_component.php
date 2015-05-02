 <?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Felhasznalok_Site_Component extends Site_Component {

    function process(){
    }

    function show(){
        echo "<h1>Felhasználók</h1>";

        $pm = PersistenceManager::getInstance();

        $r=rand(1,150000);
        $ugyfel_adatok = array(
            'azon'=>$r,
            'jelszo' => 'uj_jelszo',
            'cim'=>'Pelda utca 42.',
            'email'=>'pelda@pelda.hu',
            'telefon'=>'555555'
        );

        $ugyfel=$pm->createObject('Ugyfel',$ugyfel_adatok);

        ?><h2>Ügyfél adatok:</h2><?
        if($ugyfel)
            echo $ugyfel->to_string();


        echo "<p>Adatok módosítása:<br/> új e-mail: (üres)</p>";
        $ugyfel->setUgyfelFields(array('email'=>''));
        echo $ugyfel->to_string() . "<br/>";

        $r=rand(1,150000);
        $admin_adatok = array(
            'azon'=>$r,
            'jelszo' => 'alma1234'
        );

        $admin=$pm->createObject('Admin',$admin_adatok);


    }
}