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

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Ugyfel", array('azon'=>$azon))[0];
            $u->delete();
        }
        $this->pagination();

    }

    function show(){
      // echo $_POST["limit"].' '.$_POST['offset'].' '.$_POST['pagination'];

        $ugyfelek=$this->perm->getObjectsByLimitOffsetOrderBy("Ugyfel",$this->limit,$this->offset,'azon');
        $osszes=$this->perm->getAllObjects("Ugyfel");
        ?>

            <div class="form_box">
                <h1>Ugyfelek adatai</h1>
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
                                    echo '<td>'.$this->sorszam.'</td>';
                                    echo '<td>'.$f->getUgyfelFields()['azon'].'</td>';
                                    echo '<td>'.$f->getUgyfelFields()['cim'].'</td>';
                                    echo '<td>'.$f->getUgyfelFields()['email'].'</td>';
                                    echo '<td>'.$f->getUgyfelFields()['telefon'].'</td>';
                                    ?><td> <form action="" method="post">
                                        <input type="submit" name="editButton" value="Szerkesztés" >
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
                if(!$this->offset==0 && !$this->offset==100){
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
                if(!$this->offset==0 && !$this->offset==500){
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