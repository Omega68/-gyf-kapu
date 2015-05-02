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

    }

    function show(){
       /* ?><h2>Új ügyfél regisztrálása</h2>

        <form action="?page=ugyfel">
            <input type="submit" name="" value="Submit">
        </form>

        <?


        ?><h2>Ügyfelek</h2><?

        $pm = PersistenceManager::getInstance();
        $ugyfelek = $pm->getAllObjects("Ugyfel");
        foreach($ugyfelek as $u){
            echo "<p>". $u->to_string() ."</p>";
        }*/
        $felhasznalok=$this->perm->getAllObjects("Ugyfel");
        echo '<form method="post">
            <div class="form_box">
                <h1>Ugyfelek adatai</h1>
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>azon</th>
                            <th>cim</th>
                            <th>email</th>
                            <th>telefon</th>
                            <th>jelszo</th>
                        </tr>
                        ';
                            $count=count($felhasznalok);
                            for($i=0;$i<$count;$i++){
                                echo '<tr>';
                                echo '<td>'.$felhasznalok[$i]->getUgyfelFields()['azon'].'</td>';
                                echo '<td>'.$felhasznalok[$i]->getUgyfelFields()['cim'].'</td>';
                                echo '<td>'.$felhasznalok[$i]->getUgyfelFields()['telefon'].'</td>';
                                echo '<td>'.$felhasznalok[$i]->getUgyfelFields()['jelszo'].'</td>';
                                echo '</tr>';
                            }
                        echo '
                </table>
            </div>
            <div class="pagination">
                 <select>
                        <option value="50" selected="">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        </select> Előző
                 <span class="pagination_page_number">
                        <span class="pagination_active_page_number">1</span>
                </span>
                    Következő
                </div>
            </form>';
    }
}