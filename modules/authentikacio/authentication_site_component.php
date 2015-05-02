<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.01.
 * Time: 15:59
 */

class Authentication_Site_Component extends Site_Component{

    private $auth;

    protected function afterConstruction(){
        $this->auth=Authentication::getInstance();
    }

    function process()
    {
        if (isset($_POST['submit'])) {
            $this->auth->login($_REQUEST['azon'],$_REQUEST['pass']);
        }

        if(isset($_POST['logout'])){
            $this->auth->logout();
        }
    }

    function show()
    {
        if(!$this->auth->isUserAuthorized()) {
            ?>
            <form action="" method="post">
             <table>
                 <tr>
                     <td>Azonosító:</td>
                     <td><input type="text" name="azon"></td>
                 </tr>
                 <tr>
                 <td>Jelszó:</td>
                 <td><input type="password" name="pass"></td>
                 </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Bejelentkezés"></td>
            </tr>
             </table>
            </form>
        <?
        }
        else{
         ?>
            <form action="" method="post">
                    <input type="submit" name="logout" value="logout">
            </form>
            <?

        }
    }
}