<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Mezok_Site_Component extends Site_Component{
    private $perm;
    private $sablon_id;

    function process(){
        $this->perm=PersistenceManager::getInstance();
        if(!empty($_POST['sablon_id'])){
            $this->sablon_id=$_POST['sablon_id'];
        }
    }

    function show(){
        $adatok=array(
            'id' => "{$this->sablon_id}"
        );
        $sablonok=$this->perm->getObjectsByField("Mezo",$adatok);
        echo '<form method="post">
            <div class="form_box">
                <h1>Mezok adatai</h1>
                <input type="submit" name="addField" value="Uj mezo hozzaadasa">
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>azon</th>
                            <th>tipus</th>
                            <th>kotelezoseg</th>
                            <th>Művelet</th>
                        </tr>
                        ';
        $count=count($sablonok);
        for($i=0;$i<$count;$i++){
            echo '<tr>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['azon'].'</td>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['tipus'].'</td>';
            echo '<td>'.$sablonok[$i]->getUrlapSablonFields()['kotelezoseg'].'</td>';
            echo '<td><input type=""submit" name="ModifyField" value="Mezo módosítása"></td>';
            echo '</tr>';
        }
        echo '
                </table>
            </div>
            </form>';
    }
}