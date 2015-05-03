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
        echo $_POST['selected'].' '.$_POST['next'].' '.$_POST['previous'];
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Ugyfel", array('azon'=>$azon))[0];
            $u->delete();
        }
        if(isset($_POST['selected']) && !isset($_POST['previous']) && !isset($_POST['next'])){
            if($_POST['selected']==50){
                $this->limit=50;
                $this->offset=0;
            }else if($_POST['selected']==100){
                $this->limit=100;
                $this->offset=0;
            }else if($_POST['selected']==500){
                $this->limit=500;
                $this->offset=0;
            }

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
                }
            }else if($_POST['selected']==100 && $this->paginationNumber>0){
                if(!$this->offset==0 && !$this->offset==100){
                    $this->offset-=100;
                    $this->paginationNumber--;
                    $this->limit=100;
                }else{
                    $this->limit=100;
                    $this->offset=0;
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
                }
            }
        }
        if(isset($_POST['selected']) && isset($_POST['next'])){
            if($_POST['selected']==50){
                    $this->offset+=50;
                    $this->paginationNumber++;
            }else if($_POST['selected']==100){
                    $this->offset+=100;
                    $this->paginationNumber++;
            }
            else if($_POST['selected']==500){
                    $this->offset+=500;
                    $this->paginationNumber++;
            }
        }

    }

    function show(){
        $ugyfelek=$this->perm->getObjectsByLimitOffsetOrderBy("Ugyfel",$this->limit,$this->offset,'azon');
        ?>

            <div class="form_box">
                <h1>Ugyfelek adatai</h1>
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>#</th>
                            <th>Azonosító</th>
                            <th>Cim</th>
                            <th>E-mail</th>
                            <th>Telefon</th>
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
            <div class="pagination">
                <form action="" method="post">
                 <select name="selected" onchange="this.form.submit()">
                        <option value="50" <?if(empty($_POST['selected']) || $_POST['selected']==50) echo 'selected' ?> >50</option>
                        <option value="100" <?if($_POST['selected']==100) echo 'selected' ?>>100</option>
                        <option value="500" <?if($_POST['selected']==500) echo 'selected' ?>>500</option>
                        </select> <input type="submit" name="previous" value="Előző">
                 <span class="pagination_page_number">
                        <span class="pagination_active_page_number"><?echo $this->paginationNumber;?></span>
                </span>
                    <input type="submit" name="next" value="Következő">
                </form>
                </div>
    <?
    }
}