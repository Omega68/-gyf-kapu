<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.18.
 * Time: 17:31
 */

class Authentication
{
    static private $instance;

    static function getInstance()
    {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
    }



    //true, ha bejelentkezett és false, ha nem tartalmazza az adatbázis
public function login($felhasznaloNev, $jelszo){
    $pm = PersistenceManager::getInstance();
    $felhasznalo_adatok=array(
        'azon' => "{$felhasznaloNev}",
        'jelszo' => "{$jelszo}"
    );
    //a formalis parameterlistaban szereplo valtozokkal megnezi, hogy van-e ilyen felhasznalo az adatbazisban
    $users=$pm->getObjectsByField('felhasznalo',$felhasznalo_adatok);
    //itt megnezi, hogy visszateresi ertek milyen, ha megfelelo az azonosito-t belerakja a $_SESSION tombe
    if(is_array($users) && count($users)==1) {
        if($felhasznalo=$users[0]->getFelhasznaloFields()['azon']==$felhasznalo_adatok['azon']);
            $_SESSION['PHPSESSID']= $users[0]->getFelhasznaloFields()['azon'];
        return true;
    }
    return false;

}

    public function logout(){
        //Nem vagyok biztos benne, hogy ez igy tokeletes, de a test-eken eleg jol mukodgetett{
            unset($_SESSION['PHPSESSID']);
    }

    public function isLogged(){
        if(isset($_SESSION['PHPSESSID'])){
            return true;
        }
        else{
            return false;
        }
    }

    public function whoLoggedIn(){
        if(isset($_SESSION['PHPSESSID'])){
            return $_SESSION['PHPSESSID'];
        }
        else{
            return false;
        }
    }

}