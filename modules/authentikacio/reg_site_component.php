<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.01.
 * Time: 15:59
 */

class Registration_Site_Component extends Site_Component{

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
        if(!empty($_POST['saveUser'])){
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
            $message .= "<p> Belépni <a href=\"http://ugyfelkapu.fejlesztesgyak2015.info/\">itt</a> tud, a fenti adatokat megadva, jelszavát
                        pedig belépés után megváltoztathatja.<p>
                        Üdvözlettel,<br/>
                        Ügyfélkapu";
            // In case any of our lines are larger than 70 characters, we should use wordwrap()
            $message .= '</body></html>';
            $message = wordwrap($message, 70, "\r\n");
            // Send
            mail($_POST['email'], 'Ügyfélkapu - regisztráció', $message, $headers);
            /*$_SESSION['msg'] = 1;
            $this->msg = "Sikeres regisztráció!<br/> Azonosító: " . $_POST['azon'] . "<br/>Jelszó: " . $rPassw . "<br/>";*/
            $eredetiKod = $this->perm->getObjectsByField("ERPUgyfelKod", array("kod"=>$_GET['kod']));
            $eredetiKod[0]->delete();

            $this->auth->login($_POST['azon'], $rPassw);
            header('Location: index.php');
        }

    }

    function show()
    {
    $erp = $this->readERP();
    $ujkod = $_GET['kod'];

    $ujAzon = $this->perm->getObjectsByField("ERPUgyfelKod", array("kod"=>$ujkod));
        if($ujAzon == null ){
            echo "<p><h2>Hibás meghívókód!</h2></p>";
            return;
        }
    $ujAzon = $ujAzon[0]->getKodFields()['azon'];

    $ujUser = null;
    foreach ($erp as $e) {
        if($e['azon'] == $ujAzon){
            $ujUser = $e;
            break;
        }
    }


    ?>

            <div>
                <form action="" method="POST">

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
                </form>
                              <br/><br/>
            </div>
        </form>

    <?
    if(isset($_SESSION['msg'])){
        echo $this->msg;
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

        $adatok['azon'] = 1234;
        $adatok['nev'] = "MárBentVan AzAdatbázisban.";
        $adatok['cim'] = "4031 Debrecen, Teszt út 3.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "kruppa.kinga@gmail.com";
        $erp_u[] = $adatok;

        $adatok['azon'] = 98231;
        $adatok['nev'] = "Tóth Elek";
        $adatok['cim'] = "4031 Debrecen, Teszt út 4.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "te@hasznosnet.com";
        $erp_u[] = $adatok;

        $adatok['azon'] = 111112;
        $adatok['nev'] = "Kruppa Kinga";
        $adatok['cim'] = "4031 Debrecen, Teszt út 5.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "kruppa.kinga@gmail.com";
        $erp_u[] = $adatok;

        $adatok['azon'] = 98231222;
        $adatok['nev'] = "Tóth Elek";
        $adatok['cim'] = "4031 Debrecen, Teszt út 4.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "te@hasznosnet.com";
        $erp_u[] = $adatok;

        $adatok['azon'] = 111113;
        $adatok['nev'] = "Kruppa Kinga";
        $adatok['cim'] = "4031 Debrecen, Teszt út 6.";
        $adatok['telefon'] = "0652123456";
        $adatok['email'] = "kruppa.kinga@gmail.com";
        $erp_u[] = $adatok;

        unset($adatok);
        return $erp_u;
    }
}