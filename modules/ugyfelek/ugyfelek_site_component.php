<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Ugyfelek_Site_Component extends Site_Component{

    private $perm;
    private $uj_ugyfel_form=false;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){
        if(!empty($_POST['new_ugyfel'])){
            $this->uj_ugyfel_form=true;
        }

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
            </form>';
    }
}