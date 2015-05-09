<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.01.
 * Time: 15:59
 */

class Authentication_Site_Component extends Site_Component{

    private $auth;
    private $error;
    private $register = false;
    private $perm;
    private $registerUser = false;

    protected function afterConstruction(){
        $this->auth=Authentication::getInstance();
        $this->error = false;
        $this->perm=PersistenceManager::getInstance();

    }

    function process()
    {
        if (isset($_POST['submit'])) {
            if(!$this->auth->login($_REQUEST['azon'],$_REQUEST['pass'])){
                $this->error = true;
            }
        }

        if(isset($_POST['logout'])){
            $this->auth->logout();
        }

        if (isset($_POST['register'])){
            $this->register = true;
        }


        if(!empty($_POST['save'])){
            if(!empty($_POST['kod'])){
                $eredetiKod = $this->perm->getObjectsByField("ERPUgyfelKod", array("kod"=>$_POST['kod']));
                $eredetiKod = $eredetiKod[0]->getKodFields()['kod'];

                if($_POST['kod'] == $eredetiKod){
                    $this->registerUser = true;
                    $this->register = false;
                }

            }
            else {
                    $this->register=true;
                    $this->error = true;
                }


        }
        else if(!empty($_POST['saveUser'])){
            $adatok = array(
                'azon'=>$_POST['azon'],
                'email' => $_POST['email'],
                'cim' => $_POST['cim'],
                'telefon' => $_POST['telefon'],
                'jelszo' => '1234556'
            );
            $uk=$this->perm->createObject('Ugyfel',$adatok);
            if($uk == NULL){
                $this->registerUser = true;
            }
         }
        else if(!empty($_POST['back']))
            $this->register=false;

    }

    function show()
    {
        if(!$this->auth->isUserAuthorized()) {
            ?>
            <form action="index.php" method="post">
             <table>
                 <? if(!$this->register && !$this->registerUser){ ?>

                 <tr>
                     <td>Azonosító:</td>
                     <td><input type="text" name="azon"></td>
                 </tr>
                 <tr>
                 <td>Jelszó:</td>
                 <td><input type="password" name="pass"></td>
                 </tr>
                 <tr><td colspan="2"><input type="submit" name="submit" value="Bejelentkezés"></td></tr>
                 <tr><td colspan="2"><input type="submit" name="register" value="Regisztráció"></td></tr>
                 <? if($this->error){
                     echo '<tr><td colspan="2">Sikertelen bejelentkezés!</td></tr>';
                 }?>
                <?
                 /*$r=126;
                 $admin_adatok = array(
                    'azon'=>$r,
                    'jelszo' => 'alma1234'
                );

                $admin=$this->perm->createObject('Felhasznalo',$admin_adatok);
                 */
                ?>
                <?
                 } else {
                if ($this->register) {
                    ?>
                    <form action="" method="POST">
                        <div>
                            <table>
                                <tbody>
                                <tr>
                                    <td><h2>Regisztráció</h2></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><span>Kód:</span></td>
                                                <td><input type="text" name="kod" value=""></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <? if($this->error){
                                    echo '<tr><td colspan="2" style="color: #ff0000">Nem megfelelő meghívókód!</td></tr>';
                                }?>
                                </tbody>
                            </table>


                            <input type="submit" name="save" value="Tovább" class="save_button">
                            <input type="submit" name="back" value="Vissza" class="back_button">
                            <br/><br/>
                        </div>
                    </form>
                <?
                } else {
                    ?>

                    <form action="" method="POST">
                        <div>
                            <table>
                                <tbody>
                                <tr>
                                    <td><h2>Regisztráció</h2></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><span>Ügyfélazonosító: </span></td>
                                                <td><input type="text" name="azon" value=""></td>
                                            </tr>
                                            <tr>
                                                <td><span>E-mail</span></td>
                                                <td><input type="text" name="email" value=""></td>
                                            </tr>
                                            <tr>
                                                <td><span>Telefon</span></td>
                                                <td><input type="number" name="telefon" value=""></td>
                                            </tr>
                                            <tr>
                                                <td><span>Cím</span></td>
                                                <td><input type="text" name="cim" value=""></td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <input type="submit" name="saveUser" value="Regisztráció" class="save_button">
                            <input type="submit" name="back" value="Vissza" class="back_button">
                            <br/><br/>
                        </div>
                    </form>

                <?
                } ?>

                </table>
                </form>
            <?
            }}else{
         ?>
            <form action="index.php" method="post">
                    <input type="submit" name="logout" value="logout">
            </form>
            <?

        }
    }
}