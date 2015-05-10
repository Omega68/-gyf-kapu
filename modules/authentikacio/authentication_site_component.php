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
                if($eredetiKod == NULL){
                    $this->register=true;
                    $this->error = true;
                }
               else { $eredetiKod = $eredetiKod[0]->getKodFields()['kod'];

                if($_POST['kod'] ==  $eredetiKod){
                    $_SESSION['kod'] =  $eredetiKod;
                    $this->registerUser = true;
                    $this->register = false;
                    $_SESSION['ujkod'] = $eredetiKod;
                }
               }

            }
            else {
                    $this->register=true;
                    $this->error = true;
                }


        }
        else if(!empty($_POST['saveUser'])){
            $rPassw = rand(1000, 99999);
            $adatok = array(
                'azon'=>$_POST['azon'],
                'email' => $_POST['email'],
                'cim' => $_POST['cim'],
                'telefon' => $_POST['telefon'],
                'jelszo' => $rPassw
            );
            $uk=$this->perm->createObject('Ugyfel',$adatok);

            if($uk == NULL){
                $this->registerUser = true;
            }

            $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $message = '<html><body>';
            $message .= "<h2>Ügyfélkapu regisztráció</h2>
                         <p>Tisztelt Ügyfél!</p>
                        <p>Köszönjük regisztrációját, belépési adatai a következőek. <br/>Azonosító: " . $_POST['azon']
                        . "<br/>Jelszó: ".$rPassw."</p>";
            $message .= "<p> Belépni <a href=\"http://ugyfelkapu.fejlesztesgyak2015.info\">itt</a> tud, a fenti adatokat megadva, jelszavát
                        pedig belépés után megváltoztathatja.<p>
                        Üdvözlettel,<br/>
                        Ügyfélkapu";
            // In case any of our lines are larger than 70 characters, we should use wordwrap()
            $message .= '</body></html>';
            $message = wordwrap($message, 70, "\r\n");
            // Send
            mail($_POST['email'], 'Ügyfélkapu - regisztráció', $message, $headers);

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

                    $erp = $this->readERP();
                    $ujAzon = $this->perm->getObjectsByField("ERPUgyfelKod", array("kod"=>$_SESSION['ujkod']));
                    $ujAzon = $ujAzon[0]->getKodFields()['azon'];

                    $ujUser = null;
                    foreach ($erp as $e) {
                       if($e['azon'] == $ujAzon){
                            $ujUser = $e;
                           break;
                       }
                    }


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
                                                <td><input type="text" name="azon" value="<?echo $ujUser['azon']?>" readonly="readonly"></td>
                                            </tr>
                                            <tr>
                                                <td><span>E-mail</span></td>
                                                <td><input type="text" name="email" value="<?echo$ujUser['email']?>"></td>
                                            </tr>
                                            <tr>
                                                <td><span>Telefon</span></td>
                                                <td><input type="text" name="telefon" value="<?echo$ujUser['telefon']?>"></td>
                                            </tr>
                                            <tr>
                                                <td><span>Cím</span></td>
                                                <td><input type="text" name="cim" value="<?echo$ujUser['cim']?>"></td>
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

    private function readERP(){
        $erp_url = 'http://erp.fejlesztesgyak2015.info/api.php?module=ugyfel_api&function=allUgyfel&key=2e6766863522c270667cd91952db15f5';
        $json = file_get_contents($erp_url);

        $erp = json_decode($json, true);
        $erp_u = array();
        foreach($erp as $u){
            $adatok = array();
            $adatok['azon'] = $u['azonosito'];
            $adatok['nev'] = $u['nev'];
            $adatok['cim'] = $u['cim_irszam']." ".$u['cim_varos'].", ".$u['cim_utca_hsz'];
            $adatok['telefon'] = $u['telefon'];
            $adatok['email'] = $u['email'];
            $erp_u[] = $adatok;
            unset($adatok);
        }

        $erp_u = $this->tesztAdat($erp_u);
        return $erp_u;
    }

    private function tesztAdat($erp_u){
        $adatok = array();
        $adatok['azon'] = 67676;
        $adatok['nev'] = "Teszt Adat 1.";
        $adatok['cim'] = "4031 Debrecen, Teszt út 1.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "kruppa.kinga@gmail.com";
        $erp_u[] = $adatok;

        $adatok['azon'] = 9888;
        $adatok['nev'] = "Teszt Adat 2.";
        $adatok['cim'] = "4031 Debrecen, Teszt út 2.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "kruppa.kinga@gmail.com";
        $erp_u[] = $adatok;

        $adatok['azon'] = 1234;
        $adatok['nev'] = "MárBentVan AzAdatbázisban.";
        $adatok['cim'] = "4031 Debrecen, Teszt út 3.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "kruppa.kinga@gmail.com";
        $erp_u[] = $adatok;

        unset($adatok);
        return $erp_u;
    }
}