<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Igenylesek_Site_Component extends Site_Component{

    private $perm;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){

    }

    function show(){
        $igenylesek=$this->perm->getAllObjects("Igenyles");
        echo '<form method="post">
            <div class="form_box">
                <h1>Igénylések adatai</h1>
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
        $count=count($igenylesek);
        for($i=0;$i<$count;$i++){
            echo '<tr>';
            echo '<td>'.$igenylesek[$i]->getIgenylesFields()['azon'].'</td>';
            echo '<td>'.$igenylesek[$i]->getIgenylesFields()['statusz'].'</td>';
            echo '<td>'.$igenylesek[$i]->getIgenylesFields()['letrehozas_datuma'].'</td>';
            echo '<td>'.$igenylesek[$i]->getIgenylesFields()['utolso_datuma'].'</td>';
            echo '<td>'.$igenylesek[$i]->getIgenylesFields()['sablon_azon'].'</td>';
            echo '<td>'.$igenylesek[$i]->getIgenylesFields()['ugyfel_azon'].'</td>';
            echo '<td> <input type="submit" name="GetFilledFields" value="Kitoltott mezők lekérdezese"</td>';
            echo '</tr>';
        }
        echo '
                </table>
            </div>
            </form>';
    }
}