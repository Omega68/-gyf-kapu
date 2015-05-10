<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */
class ERP_Ugyfelek_Site_Component extends Site_Component{
    private $perm;
    private $limit=50;
    private $offset=0;
    private $paginationNumber=1;
    private $sorszam=1;
    private $szerkesztes=false;
    private $inviteAzon = -1;

    private $r;
    private $sent = false;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }
    function process(){

        if( isset($_POST['inviteButton']) && isset($_POST['email'])){
            if(!empty($_POST['email']) && !empty($_POST['inviteAzon'])){
                // The message
                $this->r = rand(1000,999999);
                while(count($this->perm->getObjectsByField("ERPUgyfelKod", array("kod"=>$this->r)))>0){
                    $this->r = rand(1000,999999);
                }

                $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $message = '<html><body>';
                $message .= "<h2>Ügyfélkapu regisztráció</h2>
                         <p>Tisztelt Ügyfél!</p>
                        <p>Köszönjük regisztrációs kérelmét, az Ön meghívó kódja a következő: " . $this->r . "</p>";
                $message .= "<p> Regisztrálni <a href=\"http://ugyfelkapu.fejlesztesgyak2015.info\">itt</a> tud, a meghívó kódját megadva.<p>
                        Üdvözlettel,<br/>
                        Ügyfélkapu";
                // In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message .= '</body></html>';
                $message = wordwrap($message, 70, "\r\n");
                // Send
                mail($_POST['email'], 'Ügyfélkapu - regisztrációs kód', $message, $headers);

                $_SESSION['inviteAzon'] = $_POST['inviteAzon'];
                $adatok = array(
                    'azon'=>$_POST['inviteAzon'],
                    'kod' => $this->r
                );
                $uk=$this->perm->createObject('ERPUgyfelKod',$adatok);
                $this->sent = true;

            }
            else {
                $this->sent = false;
            }
        }

        $this->pagination();
    }
    function show(){

        //$ugyfelek=$this->perm->getAllObjects("Ugyfel");
        $ugyfelek=$this->perm->getObjectsByLimitOffsetOrderBy("Ugyfel",$this->limit,$this->offset,'azon');

        $osszes=$this->perm->getAllObjects("Ugyfel");

        ?>

        <div class="form_box">
            <h1>ERP ügyféllista</h1>
        </div>
        <br/>
        <br/>
<?
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

  ?>
        <?
        $this->showPagination(count($erp_u));
        ?>

        <div class="listtable">
            <table style="width:100%">
                <tr>
                    <th>#</th>
                    <th>Azonosító</th>
                    <th>Név</th>
                    <th>Cim</th>
                    <th>E-mail</th>
                    <th>Telefon</th>
                    <th>Regisztációs link küldése</th>
                </tr>

                <?
                $this->sorszam=$this->offset;
                foreach($erp_u as $f){
                    echo '<tr>';
                    echo '<td>'.($this->sorszam + 1.).'</td>';
                    echo '<td>'.$f['azon'].'</td>';
                    echo '<td>'.$f['nev'].'</td>';
                    echo '<td>'.$f['cim'].'</td>';
                    echo '<td>'.$f['email'].'</td>';
                    echo '<td>'.$f['telefon'].'</td>';

                    $o = $this->perm->getObjectsByField("Ugyfel", array('azon'=>$f['azon']))[0];
                    if(!isset($o)){

                        ?>
                        <td>

                            <form action="" method="post">
                            <input type="submit" name="inviteButton" value="Meghívó küldése" >
                            <input type="hidden" name="email" value="<? echo $f['email'] ?>">
                            <input type="hidden" name="inviteAzon" value="<? echo $f['azon'] ?>">
                            </form>
                        <?
                        if($_SESSION['inviteAzon'] == $f['azon'] && $this->sent){
                            echo "<p style=\"color: red;\">Meghívó elküldve! Kód: ";
                            echo $this->r. "</p>";
                        }
                        ?>

                        </td>

                        <?
                        echo '</tr>';
                    }
                    $this->sorszam++;

                }
                ?>
            </table>
        </div>

        <?
        $this->showPagination(count($osszes));



    }
    private function pagination(){
        $this->limit=(isset($_POST['limit']) && !empty($_POST['limit'])) ? $_POST['limit'] : 50;
        $this->offset=(isset($_POST['offset']) && !empty($_POST['offset'])) ? $_POST['offset'] : 0;
        $this->paginationNumber=(isset($_POST['pagination']) && !empty($_POST['pagination'])) ? $_POST['pagination'] : 1;
        /* echo $_POST['selected'].' '.$_POST['next'].' '.$_POST['previous'].'<br>';
         echo "Limit:".$this->limit.' '."Offset:".$this->offset;*/
        if(isset($_POST['selected']) && !isset($_POST['previous']) && !isset($_POST['next']) && empty($_POST['previous']) && empty($_POST['next'])){
            //echo "belép";
            $this->limit = $_POST['selected'];
            $this->offset = 0;
            $this->paginationNumber=1;
        }
        if(isset($_POST['selected']) && isset($_POST['previous'])){
            if($_POST['selected']==50 && $this->paginationNumber>0){
                if(!$this->offset==0){
                    $this->offset-=50;
                    $this->paginationNumber--;
                    $this->limit=50;
                }else{
                    $this->limit=50;
                    $this->offset=0;
                    $this->paginationNumber=1;
                }
            }else if($_POST['selected']==100 && $this->paginationNumber>0){
                if(!$this->offset==0){
                    $this->offset-=100;
                    $this->paginationNumber--;
                    $this->limit=100;
                }else{
                    $this->limit=100;
                    $this->offset=0;
                    $this->paginationNumber=1;
                }
            }
            else if($_POST['selected']==500 && $this->paginationNumber>0){
                if(!$this->offset==0){
                    $this->offset-=500;
                    $this->paginationNumber--;
                    $this->limit=500;
                }else{
                    $this->limit=500;
                    $this->offset=0;
                    $this->paginationNumber=1;
                }
            }
        }
        if(isset($_POST['selected']) && isset($_POST['next'])){
            if($_POST['selected']==50){
                $this->offset+=50;
                $this->paginationNumber++;
                $this->limit=50;
            }else if($_POST['selected']==100){
                $this->offset+=100;
                $this->paginationNumber++;
                $this->limit=100;
            }
            else if($_POST['selected']==500){
                $this->offset+=500;
                $this->limit=500;
                $this->paginationNumber++;
            }
        }

        }
    private function showPagination($ugyfelek){
        ?>
        <div class="pagination">
            <p>Találatok száma: <? echo $ugyfelek;?></p>
            <form action="" method="post">
                <select name="selected" onchange="this.form.submit()">
                    <option value="50" <?if(empty($_POST['selected']) || $_POST['selected']==50) echo 'selected' ?> >50</option>
                    <option value="100" <?if($_POST['selected']==100) echo 'selected' ?>>100</option>
                    <option value="500" <?if($_POST['selected']==500) echo 'selected' ?>>500</option>
                </select> <input type="submit" name="previous" value="Előző">
                             <span class="pagination_page_number">
                                    <span class="pagination_active_page_number"><?echo $this->paginationNumber;?></span>
                            </span>
                <input type="hidden" value="<?echo $this->offset?>" name="offset">
                <input type="hidden" value="<?echo $this->limit?>" name="limit">
                <input type="hidden" value="<?echo $this->paginationNumber?>" name="pagination">
                <input type="submit" name="next" value="Következő">
            </form>
        </div>
    <?
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