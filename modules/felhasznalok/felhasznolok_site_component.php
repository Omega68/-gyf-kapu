 <?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Felhasznalok_Site_Component extends Site_Component {

    function process(){
    }

    function show(){
        $pm = PersistenceManager::getInstance();
        $ugyfelek=$pm->getAllObjects("Ugyfel");
        ?>
        <form method="post">
            <div class="form_box">
                <h1>Felhasználók adatai</h1>
            </div>
            <br/>
            <br/>
            <div class="listtable" style="width:50%">
                <table style="width:100%">
                    <tr>
                        <th>Azonosító</th>
                    </tr>

                    <?
                    foreach($ugyfelek as $f){
                        echo '<tr>';
                        echo '<td>'.$f->getFelhasznaloFields()['azon'].'</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
            <div class="pagination">
                <select>
                    <option value="5" selected="">5</option>
                    <option value="25">25</option>
                    <option value="50" >50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select> Előző
                 <span class="pagination_page_number">
                        <span class="pagination_active_page_number">1</span>
                </span>
                Következő
            </div>
        </form>

    <?

    }

    private function test(){
        echo "<h1>Felhasználók</h1>";

        $pm = PersistenceManager::getInstance();

        $r=rand(1,150000);
        $ugyfel_adatok = array(
            'azon'=>$r,
            'jelszo' => 'uj_jelszo',
            'cim'=>'Pelda utca 42.',
            'email'=>'pelda@pelda.hu',
            'telefon'=>'555555'
        );

        $ugyfel=$pm->createObject('Ugyfel',$ugyfel_adatok);

        ?><h2>Ügyfél adatok:</h2><?
        if($ugyfel)
            echo $ugyfel->to_string();


        echo "<p>Adatok módosítása:<br/> új e-mail: (üres)</p>";
        $ugyfel->setUgyfelFields(array('email'=>''));
        echo $ugyfel->to_string() . "<br/>";

        $r=rand(1,150000);
        $admin_adatok = array(
            'azon'=>$r,
            'jelszo' => 'alma1234'
        );

        $admin=$pm->createObject('Admin',$admin_adatok);


    }
}