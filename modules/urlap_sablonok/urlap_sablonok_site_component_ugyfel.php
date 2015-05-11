<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.10.
 * Time: 10:48
 */

class Urlap_sablonok_Site_Component_Ugyfel extends Site_Component{
private $perm;
private $limit=50;
private $offset=0;
private $paginationNumber=1;
private $sorszam=1;
private $szerkesztes=false;
private $showAddForm=false;
private $showFieldList=false;

protected function afterConstruction(){
    $this->perm=PersistenceManager::getInstance();
}

function process(){
    $this->perm=PersistenceManager::getInstance();

    if(!empty($_POST['GetFields'])) {
        $this->showFieldList = true;
    }
    
    if(isset($_POST['searchButton'])){
            if (empty($_REQUEST['ssearchString']))
                $_SESSION['skeresve']=false;
            else if (($_POST['skazon']!=1) && ($_POST['skletrehozas_datuma']!=1) && ($_POST['skallapot']!=1) && ($_POST['skadmin_azon']!=1))
                $_SESSION['skeresve']=false;
            else 
                $_SESSION['skeresve']=true;
                $_SESSION['ssearchString'] = $_POST['ssearchString'];
                $_SESSION['skazon'] = $_POST['skazon'];
                $_SESSION['skletrehozas_datuma'] = $_POST['skletrehozas_datuma'];
                $_SESSION['skallapot'] = $_POST['skallapot'];
                $_SESSION['skadmin_azon'] = $_POST['skadmin_azon'];
            }
        if(isset($_POST['resetButton'])){
              $_SESSION['skeresve']=false;
              $_SESSION['ssearchString'] = '';
              $_SESSION['skazon'] = 1;
              $_SESSION['skletrehozas_datuma'] = 1;
              $_SESSION['skallapot'] = 1;
              $_SESSION['skadmin_azon'] = 1;
            }       

    $this->pagination();
}

function show(){
     if($this->showFieldList){
        $adatok=array(
            'sablon_azon' => $_POST['sab_azon']
        );
        $mezok=$this->perm->getObjectsByField("Mezo",$adatok);
        ?>
            <form method="post">
            <div class="form_box">
            <h1>Mezők adatai - sablon: <?echo $_POST['sab_azon'];?></h1>
                <input type="submit" name="back" value="Vissza" class="back_button">
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>Azonosító</th>
                            <th>Típus</th>
                            <th>Kötelezőség</th>
                            <th>Művelet</th>
                        </tr>
        <?
        $count=count($mezok);
        for($i=0;$i<$count;$i++){
            $m = $mezok[$i]->getMezoFields();
            echo '<tr>';
            echo '<td>'.$m['azon'].'</td>';
            echo '<td>'.$m['tipus'].'</td>';
            echo '<td>';
                if($m['kotelezoseg'] == 1){
                    echo "kötelező";
                } else echo "opcionális";
            echo '</td>';
            echo '<td><input type="submit" name="ModifyField" value="Mező módosítása"></td>';
            echo '</tr>';
        }
        echo '
                </table>
            </div>
            </form>';
    } else {
        if ($_SESSION['skeresve']){
              $sablon_adatok=array();
              if($_SESSION['skazon']==1) $sablon_adatok['azon'] = $_SESSION['ssearchString'];
              if($_SESSION['skletrehozas_datuma']==1) $sablon_adatok['letrehozas_datuma'] = $_SESSION['ssearchString'];
              if($_SESSION['skallapot']==1) $sablon_adatok['allapot'] = $_SESSION['ssearchString'];
              if($_SESSION['skadmin_azon']==1) $sablon_adatok['admin_azon'] = $_SESSION['ssearchString'];
                                     
              $sablonok=$this->perm->getObjectsByFieldLimitOffsetOrderByOr("UrlapSablon",$sablon_adatok,$this->limit,$this->offset,'azon');
              $osszes=$this->perm->getObjectsByFieldOr("UrlapSablon", $sablon_adatok);           
            }
            else{
              $sablonok=$this->perm->getObjectsByLimitOffsetOrderBy("UrlapSablon",$this->limit,$this->offset,'azon');
              $osszes=$this->perm->getAllObjects("UrlapSablon");
            }
        //$sablonok=$this->perm->getObjectsByLimitOffsetOrderBy("UrlapSablon",$this->limit,$this->offset,'azon');
        //$osszes=$this->perm->getAllObjects("UrlapSablon");
        echo '<form method="post">
            <div class="form_box">
                <h1>Sablonok adatai</h1>
            </div>
            <br/>';
        ?>
            
           <div class="form_box">          
              <form action="" method="post">
                <input id="search_field" type="text" value="<? echo $_SESSION['ssearchString']; ?>" name="ssearchString" size="32">
                <input type="submit" value="Keresés" name="searchButton">
                <input type="submit" value="Alaphelyzet" name="resetButton">
                <div>
                  <input id="id_search_sel__1" type="checkbox" value="1" <? if($_SESSION['skazon']==1) echo 'checked=""';?> name="skazon">
                  <label for="id_search_sel__1">Azonosító</label>
                </div>
                <div>
                  <input id="id_search_sel__2" type="checkbox" value="1" <? if($_SESSION['skletrehozas_datuma']==1) echo 'checked=""';?> name="skletrehozas_datuma">
                  <label for="id_search_sel__2">Létrehozás dátuma</label>
                </div>
                <div>
                  <input id="id_search_sel__3" type="checkbox" value="1" <? if($_SESSION['skallapot']==1) echo 'checked=""';?> name="skallapot">
                  <label for="id_search_sel__3">Állapot</label>
                </div>
                <div>
                  <input id="id_search_sel__4" type="checkbox" value="1" <? if($_SESSION['skadmin_azon']==1) echo 'checked=""';?> name="skadmin_azon">
                  <label for="id_search_sel__4">Admin azonosító</label>
                </div>
              </form>                      
            </div>
            
            <?
            
        echo '<div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>#</th>
                            <th>Azonosító</th>
                            <th>Létrehozás dátuma</th>
                            <th>Állapot</th>
                            <th>Mezők lekérdezése</th>
                            <th>Művelet</th>
                        </tr>
                        ';
        $this->sorszam=$this->offset;
        $count=count($sablonok);
        for($i=0;$i<$count;$i++){
            $s = $sablonok[$i]->getUrlapSablonFields();
            echo '<tr>';
            echo '<td>'.($this->sorszam + 1) . '</td>';
            echo '<td>'.$s['azon'].'</td>';
            echo '<td>'.$s['letrehozas_datuma'].'</td>';
            echo '<td>'.$s['allapot'].'</td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="sab_azon" value="'.$s['azon'].'">';
            echo '<td> <input type="submit" name="GetFields" value="Mezok lekerdezese"></td>';
            echo '</form>';
            echo '<form method="post">';
            echo '<input type="hidden" name="sab_azon" value="'.$s['azon'].'">';
            echo '<td> <input type="submit" name="Choose" value="Kiválasztás"></td>';
            echo '</form>';
            ?>
            <?
            echo '</tr>';
            $this->sorszam++;
        }
        $this->showPagination(count($osszes));
    }
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
}?>
