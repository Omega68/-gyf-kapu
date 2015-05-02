<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Ugyfelek_Site_Component extends Site_Component{

    private $perm;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Ugyfel", array('azon'=>$azon))[0];
            $u->delete();
        }

    }

    function show(){
        $ugyfelek=$this->perm->getAllObjects("Ugyfel");
        ?>

            <div class="form_box">
                <h1>Ugyfelek adatai</h1>
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>Azonosító</th>
                            <th>Cim</th>
                            <th>E-mail</th>
                            <th>Telefon</th>
                            <th>Törlés</th>
                        </tr>

                    <?
                            foreach($ugyfelek as $f){
                                echo '<tr>';
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
                            }
                    ?>
                </table>
            </div>
            <div class="pagination">
                <form action="" method="post">
                 <select>
                        <option value="50" selected="">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        </select> Előző
                 <span class="pagination_page_number">
                        <span class="pagination_active_page_number">1</span>
                </span>
                    Következő
                </form>
                </div>
    <?
    }
}