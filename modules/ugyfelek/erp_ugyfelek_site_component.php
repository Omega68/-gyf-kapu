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
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Ugyfel", array('azon'=>$azon))[0];
            $u->delete();
        }
        if(isset($_POST['editButton']) && isset($_POST['szerkAzon']))
            $this->szerkesztes=true;
        if(!empty($_POST['back']) || !empty($_POST['save']))
            $this->szerkesztes=false;

        if( isset($_POST['inviteButton']) && isset($_POST['email'])){
            if(!empty($_POST['email']) && !empty($_POST['inviteAzon'])){
                // The message
                $this->r = rand(10000,990000);
                $message = "Kód: " . $this->r;
                // In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message = wordwrap($message, 70, "\r\n");
                // Send
                mail($_POST['email'], 'Ügyfélkapu - regisztrációs kód', $message);

                $this->inviteAzon = $_POST['inviteAzon'];
                $adatok = array(
                    'azon'=>$this->inviteAzon,
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
        if(!$this->szerkesztes){
        // echo $_POST["limit"].' '.$_POST['offset'].' '.$_POST['pagination'];
        $ugyfelek=$this->perm->getObjectsByLimitOffsetOrderBy("Ugyfel",$this->limit,$this->offset,'azon');
        $osszes=$this->perm->getAllObjects("Ugyfel");
        ?>

        <div class="form_box">
            <h1>ERP ügyféllista</h1>
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
                    <th>Regisztációs link küldése</th>
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
                    ?>
                    <td>

                        <form action="" method="post">
                        <input type="submit" name="inviteButton" value="Meghívó küldése" >
                        <input type="hidden" name="email" value="<? echo $f->getUgyfelFields()['email'] ?>">
                        <input type="hidden" name="inviteAzon" value="<? echo $f->getUgyfelFields()['azon'] ?>">
                        </form>
                    <?
                    if($this->inviteAzon == $f->getUgyfelFields()['azon'] && $this->sent){
                        echo "<p style=\"color: red;\">Meghívó elküldve! Kód: ";
                        echo $this->r. "</p>";
                    }
                    ?>

                    </td>

                    <?
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