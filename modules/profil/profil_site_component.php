<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Profil_Site_Component extends Site_Component{

    private $auth;
    private $user;
    private $pm;
    private $u;

    protected function afterConstruction(){
        $this->auth = Authentication::getInstance();
        $this->user = $this->auth->whoLoggedIn();
        $this->pm = PersistenceManager::getInstance();
        $this->u = $this->pm->getObject($this->user);

    }

    function process(){
        if(!empty($_POST['submit'])){
            $oldPw = $this->u->getFelhasznaloFields()['jelszo'];

            if(md5($_POST['old']) != $oldPw){
                $this->error = true;
                $this->success = false;
                if(empty($_POST['new'])){
                    $this->errorNew = true;
                }
            } else {
                if(empty($_POST['new'])){
                    $this->errorNew = true;
                } else {
                    $this->error = false;
                    $this->errorNew = false;
                    $this->u->setFelhasznaloFields(array('jelszo' => md5($_POST['new'])));
                    $this->success = true;
                }
            }
        }
    }

    function show(){

        if($this->u instanceof Admin){
            $this->adminFelulet();
        }
        else {
            $this->ugyfelFelulet();
        }

        ?><p><b>Azonosító: </b><?
        echo $this->u->getFelhasznaloFields()['azon']."</p>";

        $this->changePassword();

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

    private function changePassword(){
        ?>
            <p><b>Jelszó megváltoztatása: </b></p>
            <form action="" method="post">
            <table>

                     <tr>
                         <td>Régi jelszó:</td>
                         <td><input type="password" name="old"></td>
                     </tr>
                     <tr>
                         <td>Új jelszó:</td>
                         <td><input type="password" name="new"></td>
                     </tr>
                     <tr><td colspan="2"><input type="submit" name="submit" value="Jelszó megváltoztatása!"></td></tr>
                     <? if($this->error) {
                            echo '<tr><td colspan="2">Rossz a megadott régi jelszó!</td></tr>';

                        }
                         if($this->errorNew){
                             echo '<tr><td colspan="2">Nincs megadva új jelszó!</td></tr>';
                         }
                        if($this->success){
                            echo '<tr><td colspan="2">Sikeres jelszóváltoztatás!</td></tr>';
                    }
    }


}