<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Igenylesek_Site_Component extends Site_Component{

    private $perm;
    private $limit=50;
    private $offset=0;
    private $paginationNumber=1;
    private $sorszam=1;
    private $szerkesztes=false;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Igenyles", array('azon'=>$azon))[0];
            $u->delete();
        }

        if(!empty($_POST['save'])){
           
            $adatok = array(
                'statusz' => $_POST['statusz'],
                'letrehozas_datuma' =>  date("Y-m-d",strtotime($_POST['letrehozas_datuma'])),
                'utolso_modositas' => date("Y-m-d",strtotime($_POST['utolso_modositas'])),
                'sablon_azon' => $_POST['sablon_azon'],
                'ugyfel_azon' => $_POST['ugyfel_azon']
            );
            //$uk=$this->perm->updateObjectByFields('Ugyfel',$adatok, array("azon" => $_POST['azon']));
            $uk = $this->perm->getObjectsByField("Igenyles", array("azon" => $_POST['azon']))[0];
            if($uk->getIgenylesFields()['statusz'] != $_POST['statusz']){
                $this->sendEmail($_POST['azon'],$_POST['statusz']);
            }
            $uk->setIgenylesFields($adatok);

        }


        if(!empty($_POST['subs'])){
            $adatok = array(
                'igenyles_azon' => $_POST['igenyles_azon'],
                'email' =>  $_POST['subsEmail']
            );
            $subs = $this->perm->createObject("Feliratkozas", $adatok);
            $this->szerkesztes = true;
        }

        /*
         * <input type="hidden" name="unsubs_email" value="<? echo $s->getFeliratkozasFields()[\'email\'] ?>">
                        <input type="hidden" name="unsubs_igenyles_azon" value="<? echo $s->getFeliratkozasFields()[\'igenyles_azon\'] ?>">
                        <input type="submit" name="unsubs" value="leiratkozás">'."</td>";
         */

        if(!empty($_POST['unsubs'])){
            $adatok = array(
                'igenyles_azon' => $_POST['unsubs_igenyles_azon'],
                'email' =>  $_POST['unsubs_email']
            );
            $u = $this->perm->getObjectsByField("Feliratkozas", $adatok)[0];
            $u->delete();
            $this->szerkesztes = true;
        }


        if(isset($_POST['editButton']) && isset($_POST['szerkAzon'])){
            $this->szerkesztes=true;
            $_SESSION['szerkAzon'] = $_POST['szerkAzon'];
        }
        $this->pagination();

        $this->uploads();

    }

    function show()
    {
        if(!$this->szerkesztes ) {
            $igenylesek = $this->perm->getObjectsByLimitOffsetOrderBy("Igenyles", $this->limit, $this->offset, 'azon');
            //    $igenylesek=$this->perm->getAllObjects("Igenyles");
            echo '
            <div class="form_box">
                <h1>Igénylések adatai</h1>
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>#</th>
                            <th>Azonosító</th>
                            <th>Státusz</th>
                            <th>Létrehozás dátuma</th>
                            <th>Utolsó módosítás dátuma</th>
                            <th>Sablon azonosító</th>
                            <th>Ügyfél azonosító</th>
                            <th>Mező</th>
                            <th>Kivitelezési terv</th>
                            <th>Szerkesztés</th>
                            <th>Törlés</th>
                        </tr>
                        ';
            $count = count($igenylesek);
            $this->sorszam=$this->offset;
            for ($i = 0; $i < $count; $i++) {
                $iFields = $igenylesek[$i]->getIgenylesFields();
                echo '<tr>';
                echo '<td>'.($this->sorszam + 1.).'</td>';
                echo '<td>' . $iFields['azon'] . '</td>';
                echo '<td>' . $iFields['statusz'] . '</td>';
                echo '<td>' . date("Y.m.d",strtotime($iFields['letrehozas_datuma'])) . '</td>';
                echo '<td>' . date("Y.m.d",strtotime($iFields['utolso_modositas'])) . '</td>';
                echo '<td>' . $iFields['sablon_azon'] . '</td>';
                echo '<td>' . $iFields['ugyfel_azon'] . '</td>';

                ?>

                <td> <input type="submit" name="GetFilledFields" value="Kitoltott mezők lekérdezese"</td>

                <td>
                    <?
                    $url = $this->getDownloadLink($iFields['ugyfel_azon']);
                    if( $url == NULL){
                    ?>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="fajl" >
                        <input type="hidden" name="ugyfel_azon" value="<? echo $iFields['ugyfel_azon'] ?>">
                        <input type="submit" name="feltolt" value="Feltölt">
                    </form>
            <?} else { ?>
                       <a href="<?echo $url?>">Letöltés</a>
                    <?
                    }?>
                </td>

                <td>

                    <form action="" method="post">
                        <input type="submit" name="editButton" value="Szerkesztés" >
                        <input type="hidden" name="szerkAzon" value="<? echo $iFields['azon']?>">
                    </form></td>


                <td> <form action="" method="post">
                    <input type="submit" name="deleteButton" value="Törlés" onclick="return confirm('Biztosan törli a kiválasztott igénylést?')" >
                    <input type="hidden" name="deleteAzon" value="<? echo $iFields['azon'] ?>">
                </form></td>


            <?
                echo '</tr>';
                $this->sorszam++;
            }
            echo '
                </table>
            </div>';
            $this->showPagination(count($igenylesek));
        }
        else{
            $lekerdezes_adatok=array(
                'azon'=>"{$_SESSION['szerkAzon']}"
            );
            //var_dump($lekerdezes_adatok);
            $customer=$this->perm->getObjectsByField('Igenyles',$lekerdezes_adatok);
            // var_dump($customer);
            ?>
            <form action="" method="POST">
            <div class="form_box">
                <h1>Igénylés adatainak módosítása</h1>
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
                                        <td><input type="text" name="azon" readonly="readonly"  value="<? echo $customer[0]->getIgenylesFields()['azon'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Státusz</span></td>
                                        <td><input type="text" name="statusz" value="<?echo $customer[0]->getIgenylesFields()['statusz']?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Létrehozás dátuma</span></td>
                                        <td><input type="date" name="letrehozas_datuma" value="<?echo date("Y-m-d",strtotime($customer[0]->getIgenylesFields()['letrehozas_datuma']))?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Utolsó dátuma</span></td>
                                        <td><input type="date" name="utolso_modositas" value="<?echo date("Y-m-d",strtotime($customer[0]->getIgenylesFields()['utolso_modositas']))?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Sablon azonosító</span></td>
                                        <td><input type="text" name="sablon_azon" value="<?echo $customer[0]->getIgenylesFields()['sablon_azon']?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Ügyfel azonosító</span></td>
                                        <td><input type="text" name="ugyfel_azon" value="<?echo $customer[0]->getIgenylesFields()['ugyfel_azon']?>"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <p>Igénylés állapotára való feliratkozás:</p>
                <form method="post" enctype="multipart/form-data">
                    E-mail: <input type="text" name="subsEmail" >
                    <input type="hidden" name="igenyles_azon" value="<? echo $customer[0]->getIgenylesFields()['azon'] ?>">
                    <input type="submit" name="subs" value="Feliratkozás">
                </form>
                <p><b>Igénylés állapotára feliratkozottak:</b></p>
                <?
                    //$subscribers = $this->perm->getAllObjects("Feliratkozas");
                $subscribers = $this->perm->getObjectsByField("Feliratkozas", array("igenyles_azon"=> $customer[0]->getIgenylesFields()['azon']));

                echo '<table>';
                    foreach($subscribers as $s){
                        echo "<tr>";
                        echo "<td>".$s->getFeliratkozasFields()['email']."</td>";
                        ?> <td>
                        <input type="hidden" name="unsubs_email" value="<? echo $s->getFeliratkozasFields()['email'] ?>">
                        <input type="hidden" name="unsubs_igenyles_azon" value="<? echo $s->getFeliratkozasFields()['igenyles_azon'] ?>">
                        <input type="submit" name="unsubs" value="leiratkozás">
                        </td><?
                    }
                if(empty($subscribers)){
                    echo "<tr><td>";
                    echo "Nincs feliratkozó.";
                    echo "</td></tr>";
                }
                echo "</table>";
                ?>

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

    private function uploads(){
        $target_dir = realpath(__DIR__ . '/../../ufkapu-uploads');
        if(isset($_POST["feltolt"])) {
            if (!$_FILES['fajl']['error']){
                $tmp_name = $_FILES["fajl"]["tmp_name"];
                $name = $_FILES["fajl"]["name"];
                $ext  = pathinfo($name)['extension'];
                //$file = $_FILES['fajl'];
                //move_uploaded_file($tmp_name, $target_dir."/".spl_object_hash($file).".".($name[count($name)-1]));
                move_uploaded_file($tmp_name, $target_dir."/"."kiv_terv-".$_POST['ugyfel_azon'].".".$ext);
                $terv = $this->perm->createObject("KivitelezesiTerv", array("ugyfel_azon" => $_POST['ugyfel_azon'],
                    "path"=>"kiv_terv-".$_POST['ugyfel_azon'].".".$ext));

            }
        }
    }

    private function getDownloadLink($ugyfel_azon){
        //$target_dir = realpath(__DIR__ . '/../../ufkapu-uploads');
        $target_dir = "http://hazik.fejlesztesgyak2015.info/kruppa_kinga/ufkapu-uploads";
        $v = NULL;
        $terv = $this->perm->getObjectsByField("KivitelezesiTerv", array("ugyfel_azon"=>$ugyfel_azon));
        if(!empty($terv)){
            $v = $target_dir."/".$terv[0]->getKivitelezesiTervFields()['path'];
        }
        return $v;
    }

    private function sendEmail($igenyles_azon, $statusz){
        $subscribers = $this->perm->getObjectsByField("Feliratkozas", array("igenyles_azon"=> $igenyles_azon));
        //$igenyles_azon
        $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = '<html><body>';
        $message .= "<h2>Ügyfélkapu - Igénylés állapotának változása</h2>
                    <p>Tisztelt Felhasználó!</p>
                    <p>A(z) <? echo $igenyles_azon ?> számú igénylés státusza megváltozott. Az új státusz: <? echo $statusz ?>. </p>";
        $message .= "<p>Üdvözlettel,<br/>
                        Ügyfélkapu<p>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message .= '</body></html>';
        $message = wordwrap($message, 70, "\r\n");
        // Send

        foreach($subscribers as $s){
            mail($s->getFeliratkozasFields()['email'], 'Ügyfélkapu - igénylés állapotának változása', $message, $headers);
        }
    }
}