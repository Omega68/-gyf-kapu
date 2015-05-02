<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Urlap_sablonok_Site_Component extends Site_Component{

    private $perm;


    function process(){
        $this->perm=PersistenceManager::getInstance();
    }

    function show(){

        $sablonok=$this->perm->getAllObjects("UrlapSablon");
        echo '<form method="post">
            <div class="form_box">
                <h1>Sablonok adatai</h1>
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>azon</th>
                            <th>letrehozas datuma</th>
                            <th>allapot</th>
                            <th>admin_azon</th>
                            <th>Művelet</th>
                        </tr>
                        ';
        $count=count($sablonok);
        for($i=0;$i<$count;$i++){
            echo '<tr>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['azon'].'</td>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['letrehozas_datuma'].'</td>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['allapot'].'</td>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['admin_azon'].'</td>';
            echo '<td> <input type="submit" name="GetFields" value="Mezok lekerdezese"</td>';
            echo '</tr>';
        }
        echo '
                </table>
            </div>
            </form>';
    }
}