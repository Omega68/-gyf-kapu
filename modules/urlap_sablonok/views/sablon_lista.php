<?
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.03.
 * Time: 18:00
 */
$sablonok=$this->perm->getAllObjects("UrlapSablon");
echo '<form action="" method="post">
                <button type="submit" name="new" value="new">Új sablon hozzáadása</button>
        </form>';
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
    echo '<input type="hidden" name="sablon_id" value="'.$sablonok[$i]->getUrlapSablonFields()['id'].'">';
    echo '<td> <input type="submit" name="GetFields" value="Mezok lekerdezese"></td>';
    echo '</tr>';
}
echo '
</table>
</div>
</form>';
