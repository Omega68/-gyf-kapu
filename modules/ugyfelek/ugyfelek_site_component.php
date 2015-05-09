<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */
class Ugyfelek_Site_Component extends Site_Component{
    private $perm;
    private $limit=50;
    private $offset=0;
    private $paginationNumber=1;
    private $sorszam=1;
    private $szerkesztes=false;
    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }
    function process(){
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Ugyfel", array('azon'=>$azon))[0];
            $u->delete();
        }
        if(isset($_POST['editButton']) && isset($_POST['szerkAzon']))
            $this->szerkesztes=true;

        if(isset($_POST['searchButton'])){
            if (empty($_REQUEST['usearchString']))
                $_SESSION['ukeresve']=false;
            else if (($_POST['ukazon']!=1) && ($_POST['ukcim']!=1) && ($_POST['ukemail']!=1) && ($_POST['uktelefon']!=1))
                $_SESSION['ukeresve']=false;
            else 
                $_SESSION['ukeresve']=true;
            $_SESSION['usearchString'] = $_POST['usearchString'];
            $_SESSION['ukazon'] = $_POST['ukazon'];
            $_SESSION['ukcim'] = $_POST['ukcim'];
            $_SESSION['ukemail'] = $_POST['ukemail'];
            $_SESSION['uktelefon'] = $_POST['uktelefon'];
            }
        if(isset($_POST['resetButton'])){
            $_SESSION['ukeresve']=false;
            $_SESSION['usearchString'] = '';
            $_SESSION['ukazon'] = 1;
            $_SESSION['ukcim'] = 1;
            $_SESSION['ukemail'] = 1;
            $_SESSION['uktelefon'] = 1;
            }

        if(!empty($_POST['save'])){
                    $adatok = array(
                        'email' => $_POST['email'],
                        'cim' => $_POST['cim'],
                        'telefon' => $_POST['telefon']
                    );
                $uk=$this->perm->updateObjectByFields('Ugyfel',$adatok, array("azon" => $_POST['azon']));
            }


        if(!empty($_POST['back']) || !empty($_POST['save']))
            $this->szerkesztes=false;
        $this->pagination();
    }
    function show(){
        if(!$this->szerkesztes){
        // echo $_POST["limit"].' '.$_POST['offset'].' '.$_POST['pagination'];
        //$ugyfelek=$this->perm->getObjectsByLimitOffsetOrderBy("Ugyfel",$this->limit,$this->offset,'azon');
        //$osszes=$this->perm->getAllObjects("Ugyfel");
        
        if ($_SESSION['ukeresve']){
            $ugyfel_adatok=array();
            if($_SESSION['ukazon']==1) $ugyfel_adatok['azon'] = $_SESSION['usearchString'];
            if($_SESSION['ukcim']==1) $ugyfel_adatok['cim'] = $_SESSION['usearchString'];
            if($_SESSION['ukemail']==1) $ugyfel_adatok['email'] = $_SESSION['usearchString'];
            if($_SESSION['uktelefon']==1) $ugyfel_adatok['telefon'] = $_SESSION['usearchString'];
            
            $ugyfelek=$this->perm->getObjectsByFieldLimitOffsetOrderByOr("Ugyfel",$ugyfel_adatok,$this->limit,$this->offset,'azon');
            $osszes=$this->perm->getObjectsByFieldOr("Ugyfel", $ugyfel_adatok);          
        }
        else{
          $ugyfelek=$this->perm->getObjectsByLimitOffsetOrderBy("Ugyfel",$this->limit,$this->offset,'azon');
          $osszes=$this->perm->getAllObjects("Ugyfel");
        }
        
        ?>

        <div class="form_box">
            <h1>Ugyfelek adatai</h1>
        </div>
        <br/>
        
        <div class="form_box">          
          <form action="" method="post">
            <input id="search_field" type="text" value="<? echo $_SESSION['usearchString']; ?>" name="usearchString" size="32">
            <input type="submit" value="Keresés" name="searchButton">
            <input type="submit" value="Alaphelyzet" name="resetButton">
            <div>
              <input id="id_search_sel__1" type="checkbox" value="1" <? if($_SESSION['ukazon']==1) echo 'checked=""';?> name="ukazon">
              <label for="id_search_sel__1">Azonosító</label>
            </div>
            <div>
              <input id="id_search_sel__2" type="checkbox" value="1" <? if($_SESSION['ukcim']==1) echo 'checked=""';?> name="ukcim">
              <label for="id_search_sel__2">Cím</label>
            </div>
            <div>
              <input id="id_search_sel__3" type="checkbox" value="1" <? if($_SESSION['ukemail']==1) echo 'checked=""';?> name="ukemail">
              <label for="id_search_sel__3">E-mail</label>
            </div>
            <div>
              <input id="id_search_sel__4" type="checkbox" value="1" <? if($_SESSION['uktelefon']==1) echo 'checked=""';?> name="uktelefon">
              <label for="id_search_sel__4">Telefon</label>
            </div>
          </form>                      
        </div>
                
        <br/>
        <br/>
        <?
        $this->showPagination(count($osszes));
        ?>

        <div class="listtable">
            <table style="width:100%">
                <tr>
                    <th>#</th>
                    <th>Azonosító</th>
                    <th>Cim</th>
                    <th>E-mail</th>
                    <th>Telefon</th>
                    <th>Szerkesztés</th>
                    <th>Törlés</th>
                </tr>

                <?
                $this->sorszam=$this->offset;
                foreach($ugyfelek as $f){
                    echo '<tr>';
                    echo '<td>'.($this->sorszam + 1.).'</td>';
                    echo '<td>'.$f->getUgyfelFields()['azon'].'</td>';
                    echo '<td>'.$f->getUgyfelFields()['cim'].'</td>';
                    echo '<td>'.$f->getUgyfelFields()['email'].'</td>';
                    echo '<td>'.$f->getUgyfelFields()['telefon'].'</td>';
                    ?><td> <form action="" method="post">
                            <input type="submit" name="editButton" value="Szerkesztés" >
                            <input type="hidden" name="szerkAzon" value="<? echo $f->getUgyfelFields()['azon']?>">
                        </form></td>
                    <?
                    ?><td> <form action="" method="post">
                        <input type="submit" name="deleteButton" value="Törlés" onclick="return confirm('Biztosan törli a kiválasztott ügyfelet?')" >
                        <input type="hidden" name="deleteAzon" value="<? echo $f->getUgyfelFields()['azon'] ?>">
                    </form></td>
                    <?
                    // echo '<td>'.$f->getUgyfelFields()['jelszo'].'</td>';
                    echo '</tr>';
                    $this->sorszam++;
                }
                ?>
            </table>
        </div>

        <?
        $this->showPagination(count($osszes));
        }
        else{
            $lekerdezes_adatok=array(
                'azon'=>"{$_POST['szerkAzon']}"
            );
            //var_dump($lekerdezes_adatok);
            $customer=$this->perm->getObjectsByField('Ugyfel',$lekerdezes_adatok);
           // var_dump($customer);
          ?>
            <form action="" method="POST">
        <div class="form_box">
        <h1>Ugyfel adatainak módosítása</h1>
        <input type="submit" name="save" value="Mentés" class="save_button">
        <input type="submit" name="back" value="Vissza" class="back_button">
        <br/>
        <br/>
        <div>
           <table class="formtable">
                <tbody>
                <tr>
                    <td valign="top">
                            <table>
                            <tbody>
                            <tr>
                                <td><span>Azonosító</span></td>
                                <td><input type="text" name="azon"  value="<? echo $customer[0]->getUgyfelFields()['azon'] ?>"></td>
                            </tr>
                            <tr>
                                <td><span>E-mail</span></td>
                                <td><input type="text" name="email" value="<?echo $customer[0]->getUgyfelFields()['email']?>"></td>
                            </tr>
                            <tr>
                                <td><span>Telefon</span></td>
                                <td><input type="number" name="telefon" value="<?echo $customer[0]->getUgyfelFields()['telefon']?>"></td>
                            </tr>
                            <tr>
                                <td><span>Cím</span></td>
                                <td><input type="text" name="cim" value="<?echo $customer[0]->getUgyfelFields()['cim']?>"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</form><?
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
}