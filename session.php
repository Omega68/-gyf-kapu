<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.18.
 * Time: 17:31
 */

require_once("../persistence_manager.php");
require_once("../ugyfel.php");
require_once("../felhasznalo.php");

//Nem biztos, hogy teljesen jo. az elmeletileg egyedi felhasznalo azonositot hasznalja mint session_id-t.
class Authentication
{

public function __construct(){
    ini_set('session.use_only_cookies', true);
    session_start();
}

    //true, ha bejelentkezett és false, ha nem tartalmazza az adatbázis
public function login($felhasznaloNev, $jelszo){
   /* if(isset($_COOKIE['PHPSESSID']))
    {
        echo 'The session ID has been store in a cookie'."<br>";
    }*/
    if(PHP_SESSION_DISABLED==session_status())
        session_start();
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

    //elotte letre kell hozni a session_start-tal egy sessiont-t, utana van ertelme ezt meghivni.
    public function logout(){
        //Nem vagyok biztos benne, hogy ez igy tokeletes, de a test-eken eleg jol mukodgetett
        if(PHP_SESSION_ACTIVE==session_status()) {
            unset($_SESSION['PHPSESSID']);
            session_destroy();
        }
    }


    //eredetileg boolean változóval akartam true, és false értéket visszaadni, de a php-ban nem igazan mukodik vagy nem tudom,
    // igy maradt a "logged", vagy "not logged" dolog
    public function isLogged(){
        if(isset($_SESSION['PHPSESSID'])){
            return "logged";
        }
        else{
            return "not logged";
        }
    }

    public function whoLoggedIn(){
        if(isset($_SESSION['PHPSESSID'])){
            return $_SESSION['PHPSESSID'];
        }
        else{
            return "nobody";
        }
    }
/*
    public function egyeb()
    {
//$_SESSION['teszt']='123';

        $_SESSION['szamlalo']++;

//$_SESSION['bejelentkezve_id']=123;

//debug
        echo "<pre>";
        var_dump($_SESSION);
        echo "</pre>";


        $user = $autentikacio->login('elek', '123');

        $autentikacio->logout();

        $user = $autentikacio->getLoggedInUser();
    }*/
}