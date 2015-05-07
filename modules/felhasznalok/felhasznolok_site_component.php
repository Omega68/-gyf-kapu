 <?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Felhasznalok_Site_Component extends Site_Component {

    private $limit=50;
    private $offset=0;
    private $paginationNumber=1;
    private $sorszam=1;
    private $r;
    private $sent = false;


    function process(){
        $pm = PersistenceManager::getInstance();

        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $pm->getObjectsByField("Felhasznalo", array('azon'=>$azon))[0];
            $u->delete();
        }
        
        if(isset($_POST['searchButton'])){
            if (empty($_REQUEST['fsearchString']))
                $_SESSION['fkeresve']=false;
            else if (($_POST['fkazon']!=1) && ($_POST['fkemail']!=1))
                $_SESSION['fkeresve']=false;
            else 
                $_SESSION['fkeresve']=true;
            $_SESSION['fsearchString'] = $_POST['fsearchString'];
            $_SESSION['fkazon'] = $_POST['fkazon'];
            $_SESSION['fkemail'] = $_POST['fkemail'];
            }
        if(isset($_POST['resetButton'])){
            $_SESSION['fkeresve']=false;
            $_SESSION['fsearchString'] = '';
            $_SESSION['fkazon'] = 1;
            $_SESSION['fkemail'] = 1;
            }

        if( isset($_POST['inviteButton'])){
            if(!empty($_POST['email']) ){
                // The message
                $this->r = rand(10000,990000);
                $message = "Kód: " . $this->r;
                // In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message = wordwrap($message, 70, "\r\n");
                // Send
                mail($_POST['email'], 'Ügyfélkapu - regisztrációs kód', $message);
                $this->sent = true;
            }
            else {
                $this->sent = false;
            }
        }



        $this->pagination();

    }

    function show(){
        $pm = PersistenceManager::getInstance();
        //$ugyfelek=$pm->getObjectsByLimitOffsetOrderBy("Felhasznalo",$this->limit,$this->offset,'azon');
        //$osszes=$pm->getAllObjects("Felhasznalo");
        
        if ($_SESSION['fkeresve']){
            $ugyfel_adatok=array();
            if($_SESSION['fkazon']==1) $ugyfel_adatok['azon'] = $_SESSION['fsearchString'];
            if($_SESSION['fkemail']==1) $ugyfel_adatok['email'] = $_SESSION['fsearchString'];
          
            $ugyfelek=$pm->getObjectsByFieldLimitOffsetOrderByOr("Felhasznalo",$ugyfel_adatok,$this->limit,$this->offset,'azon');
            $osszes=$pm->getObjectsByFieldOr("Felhasznalo", $ugyfel_adatok);           
        }
        else{
          $ugyfelek=$pm->getObjectsByLimitOffsetOrderBy("Felhasznalo",$this->limit,$this->offset,'azon');
          $osszes=$pm->getAllObjects("Felhasznalo");
        }

        ?>


            <div class="form_box">
                <h1>Felhasználók adatai</h1>
            </div>
            
            <br/>
        
            <div class="form_box">          
              <form action="" method="post">
                <input id="search_field" type="text" value="<? echo $_SESSION['fsearchString']; ?>" name="fsearchString" size="32">
                <input type="submit" value="Keresés" name="searchButton">
                <input type="submit" value="Alaphelyzet" name="resetButton">
                <div>
                  <input id="id_search_sel__1" type="checkbox" value="1" <? if($_SESSION['fkazon']==1) echo 'checked=""';?> name="fkazon">
                  <label for="id_search_sel__1">Azonosító</label>
                </div>
                <div>
                  <input id="id_search_sel__3" type="checkbox" value="1" <? if($_SESSION['fkemail']==1) echo 'checked=""';?> name="fkemail">
                  <label for="id_search_sel__3">E-mail</label>
                </div>
              </form>                      
            </div>
            
            <br/>
            <br/>

            <!--Új felhasználó - meghívó kód küldése:
            <form action="" method="post">
                E-mail: <input type="text" name="email" value="">
                <input type="submit" name="inviteButton" value="Meghívó küldése!" >
            </form></td>
            -->
            <?
        /*
                if($this->sent){
                    echo "<p style=\"color: red;\">Meghívó elküldve! Kód: ";
                    echo $this->r. "</p>";
                }
        */
            ?>

            <form method="post">
            <div class="listtable" style="width:100%">
            <?         $this->showPagination(count($osszes));
            ?>
                <table style="width:100%">
                    <tr>
                        <th>#</th>
                        <th>Azonosító</th>
                        <th>E-mail</th>
                        <th>Szerkesztés</th>
                        <th>Törlés</th>
                    </tr>

                    <?
        $this->sorszam=$this->offset;
        foreach($ugyfelek as $f){
                        echo '<tr>';
                        echo '<td>'.($this->sorszam + 1) . '</td>';
                        echo '<td>'.$f->getFelhasznaloFields()['azon'].'</td>';
                        echo '<td>'.$f->getFelhasznaloFields()['email'].'</td>';
                        ?><td> <form action="" method="post">
                                <input type="submit" name="editButton" value="Szerkesztés" >
                            </form></td>
                        <?
                        ?><td> <form action="" method="post">
                            <input type="submit" name="deleteButton" value="Törlés" onclick="return confirm('Biztosan törli a kiválasztott felhasználót?')" >
                            <input type="hidden" name="deleteAzon" value="<? echo $f->getFelhasznaloFields()['azon'] ?>">
                        </form></td>
                        <?
                        echo '</tr>';
                        $this->sorszam++;

                    }
                    ?>
                </table>
            </div>
        </form>

    <?
        $this->showPagination(count($osszes));
    }

    private function test(){
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
}