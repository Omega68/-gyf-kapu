﻿<?php
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
    private $showFieldList=false;

    protected function afterConstruction(){
        $this->perm=PersistenceManager::getInstance();
    }

    function process(){
        if(isset($_POST['deleteButton']) && isset($_POST['deleteAzon'])){
            $azon = $_POST['deleteAzon'];
            $u = $this->perm->getObjectsByField("Igenyles", array('azon'=>$azon))[0];
            $u->delete();
        }

	if(!empty($_POST['GetFields'])) {
            $this->showFieldList = true;
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
        
        if(isset($_POST['searchButton'])){
            if (empty($_REQUEST['isearchString']))
                $_SESSION['ikeresve']=false;
            else if (($_POST['ikazon']!=1) && ($_POST['ikstatusz']!=1) && ($_POST['ikletrehozas_datuma']!=1) && ($_POST['ikutolso_modositas']!=1) && ($_POST['iksablon_azon']!=1) && ($_POST['ikugyfel_azon']!=1))
                $_SESSION['ikeresve']=false;
            else 
                $_SESSION['ikeresve']=true;
                $_SESSION['isearchString'] = $_POST['isearchString'];
                $_SESSION['ikazon'] = $_POST['ikazon'];
                $_SESSION['ikletrehozas_datuma'] = $_POST['ikletrehozas_datuma'];
                $_SESSION['ikutolso_modositas'] = $_POST['ikutolso_modositas'];
                $_SESSION['ikstatusz'] = $_POST['ikstatusz'];
                $_SESSION['iksablon_azon'] = $_POST['iksablon_azon'];
                $_SESSION['ikugyfel_azon'] = $_POST['ikugyfel_azon'];
            }
        if(isset($_POST['resetButton'])){
            $_SESSION['ikeresve']=false;
            $_SESSION['isearchString'] = '';
            $_SESSION['ikazon'] = 1;
            $_SESSION['ikletrehozas_datuma'] = 1;
            $_SESSION['ikutolso_modositas'] = 1;
            $_SESSION['ikstatusz'] = 1;
            $_SESSION['iksablon_azon'] = 1;
            $_SESSION['ikugyfel_azon'] = 1;
            }
        
        if(isset($_POST['printButton']) && isset($_POST['printAzon'])){
            $nyom = new PDF_Gen($_POST['printAzon']);
            $nyom->createPDF();
        } 
            
        $this->pagination();

        $this->uploads();

    }

    function show()
    {
	if($this->showFieldList){
            echo $_POST['igeny_azon'];
            $adatok=array(
                'igenyles_azon' => "".$_POST['igeny_azon']
            );
            $kmezok=$this->perm->getObjectsByField("KitoltottMezo",$adatok);
            echo '<form method="post">
            <div class="form_box">
            <h1>Kitöltött mezők adatai</h1>
                <input type="submit" name="back" value="Vissza" class="back_button">
            </div>
            <br/>
            <br/>
            <div class="listtable">
                <table style="width:100%">
                        <tr>
                            <th>Igénylés Azonosító</th>
                            <th>Tartalom</th>
                            <th>Mező azon</th>
                        </tr>
                        ';
            $count=count($kmezok);
            for($i=0;$i<$count;$i++){
                echo '<tr>';
                echo '<td>'.$kmezok[$i]->getKitoltottMezoFields()['igenyles_azon'].'</td>';
                echo '<td>'.$kmezok[$i]->getKitoltottMezoFields()['tartalom'].'</td>';
                echo '<td>'.$kmezok[$i]->getKitoltottMezoFields()['mezo_azon'].'</td>';
                echo '</tr>';
            }
            echo '
                </table>
            </div>
            </form>';
        } else if(!$this->szerkesztes ) {
          if ($_SESSION['ikeresve']){
              $igenyles_adatok=array();
              if($_SESSION['ikazon']==1) $igenyles_adatok['azon'] = $_SESSION['isearchString'];
              if($_SESSION['ikletrehozas_datuma']==1) $igenyles_adatok['letrehozas_datuma'] = $_SESSION['isearchString'];
              if($_SESSION['ikutolso_modositas']==1) $igenyles_adatok['utolso_modositas'] = $_SESSION['isearchString'];
              if($_SESSION['ikstatusz']==1) $igenyles_adatok['statusz'] = $_SESSION['isearchString'];
              if($_SESSION['iksablon_azon']==1) $igenyles_adatok['sablon_azon'] = $_SESSION['isearchString'];
              if($_SESSION['ikugyfel_azon']==1) $igenyles_adatok['ugyfel_azon'] = $_SESSION['isearchString'];
                                     
              $igenylesek=$this->perm->getObjectsByFieldLimitOffsetOrderByOr("Igenyles",$igenyles_adatok,$this->limit,$this->offset,'azon');    
            }
            else{
              $igenylesek=$this->perm->getObjectsByLimitOffsetOrderBy("Igenyles",$this->limit,$this->offset,'azon');
            }
        
            //$igenylesek = $this->perm->getObjectsByLimitOffsetOrderBy("Igenyles", $this->limit, $this->offset, 'letrehozas_datuma');
            //    $igenylesek=$this->perm->getAllObjects("Igenyles");
            echo '
            <div class="form_box">
                <h1>Igénylések adatai</h1>
            </div>
            <br/>';
            ?>
            
           <div class="form_box">          
              <form action="" method="post">
                <input id="search_field" type="text" value="<? echo $_SESSION['isearchString']; ?>" name="isearchString" size="32">
                <input type="submit" value="Keresés" name="searchButton">
                <input type="submit" value="Alaphelyzet" name="resetButton">
                <div>
                  <input id="id_search_sel__1" type="checkbox" value="1" <? if($_SESSION['ikazon']==1) echo 'checked=""';?> name="ikazon">
                  <label for="id_search_sel__1">Azonosító</label>
                </div><div>
                  <input id="id_search_sel__2" type="checkbox" value="1" <? if($_SESSION['ikstatusz']==1) echo 'checked=""';?> name="ikstatusz">
                  <label for="id_search_sel__2">Státusz</label>
                </div>                
                <div>
                  <input id="id_search_sel__3" type="checkbox" value="1" <? if($_SESSION['ikletrehozas_datuma']==1) echo 'checked=""';?> name="ikletrehozas_datuma">
                  <label for="id_search_sel__3">Létrehozás dátuma</label>
                </div>
                <div>
                  <input id="id_search_sel__4" type="checkbox" value="1" <? if($_SESSION['ikutolso_modositas']==1) echo 'checked=""';?> name="ikutolso_modositas">
                  <label for="id_search_sel__4">Utolsó módosítás</label>
                </div> 
                <div>
                  <input id="id_search_sel__5" type="checkbox" value="1" <? if($_SESSION['iksablon_azon']==1) echo 'checked=""';?> name="iksablon_azon">
                  <label for="id_search_sel__5">Sablon azonosító</label>
                </div>
                <div>
                  <input id="id_search_sel__6" type="checkbox" value="1" <? if($_SESSION['ikugyfel_azon']==1) echo 'checked=""';?> name="ikugyfel_azon">
                  <label for="id_search_sel__6">Ügyfél azonosító</label>
                </div>
              </form>                      
            </div>
            
            <?            
            
            echo '<div class="listtable">
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
                            <th>Nyomtatás</th>
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

                ?>	<form method="post">

                <td> 	<input type="submit" name="GetFields" value="Kitöltött mezők lekérdezese"</td>
			<input type="hidden" name="igeny_azon" value="<?echo $iFields['azon']?>" >
			</form>

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
                       <a href="<?echo $url?>" target="_blank">Letöltés</a>
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
                
                <td> <form action="" method="post">
                        <input type="submit" name="printButton" value="Nyomtatás" >
                        <input type="hidden" name="printAzon" value="<? echo $iFields['azon'] ?>">
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
                                        <td><select name="statusz">
                                                <option value="Aktív">Aktív</option>
                                                <option value="Passzív">Passzív</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>Létrehozás dátuma</span></td>
                                        <td><input type="date" name="letrehozas_datuma" value="<?echo date("Y-m-d",strtotime($customer[0]->getIgenylesFields()['letrehozas_datuma']))?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Utolsó módosítás</span></td>
                                        <td><input type="date" name="utolso_modositas" value="<?echo date("Y-m-d",strtotime($customer[0]->getIgenylesFields()['utolso_modositas']))?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Sablon azonosító</span></td>
                                        <td><input type="text" name="sablon_azon" value="<?echo $customer[0]->getIgenylesFields()['sablon_azon']?>"  readonly="readonly"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Ügyfel azonosító</span></td>
                                        <td><input type="text" name="ugyfel_azon" value="<?echo $customer[0]->getIgenylesFields()['ugyfel_azon']?>"  readonly="readonly"></td>
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
        $target_dir = "http://ugyfelkapu.fejlesztesgyak2015.info/ufkapu-uploads";
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
                    <p>A(z) ";
        $message.= $igenyles_azon;
        $message .= " számú igénylés státusza megváltozott. Az új státusz: ";
        $message .= $statusz;
        $message .= "</p>";
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