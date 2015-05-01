<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Profil_Site_Component extends Site_Component{
    function process(){
    }

    function show(){
        
        $auth = Authentication::getInstance();
        $user = $auth->whoLoggedIn();
        $pm = PersistenceManager::getInstance();
        $u = $pm->getObject($user);

        if($u instanceof Admin){
            $this->adminFelulet();
        }
        else {
            $this->ugyfelFelulet();
        }

    }

    private function adminFelulet(){
        ?>
        <h1>Üdv, Admin!</h1>
    <?
    }

    private function ugyfelFelulet(){
        ?>
        <h1>Üdv, Ügyfél!</h1>
    <?
    }


}