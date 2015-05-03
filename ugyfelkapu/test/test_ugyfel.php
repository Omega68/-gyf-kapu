<html>
<head><title>�gyf�l teszt</title></head>
<body>
<h1>Teszt</h1>
<?php
require_once("../persistence_manager.php");
require_once("../model/ugyfel.php");
require_once("../model/felhasznalo.php");
require_once("../model/admin.php");

$br = "<br/>";

//$pm = PersistenceManager::getInstance();

$r=rand(1,150000); 

$felhasznalo_adatok=array(
  'azon' => "{$r}",
  'jelszo' => 'jelszo'
);

//$felhasznalo=$pm->createObject('felhasznalo',$felhasznalo_adatok);
$felhasznalo = new Felhasznalo();
$felhasznalo->create($felhasznalo_adatok);
echo 'Az �j felhaszn�l�: ';

echo implode(', ',$felhasznalo->getFelhasznaloFields()).'<br/>';

echo $br . $br;
echo "Adatok m�dos�t�sa:<br/> �j jelsz�: alma<br/>";
$felhasznalo->setFelhasznaloFields(array('jelszo'=>'alma'));
echo implode(', ',$felhasznalo->getFelhasznaloFields()).'<br/>';

echo $br . $br;
echo "Adatok m�dos�t�sa:<br/> �j jelsz�: alma1234<br/>";
$felhasznalo->setFelhasznaloFields(array('jelszo'=>'alma1234'));
echo implode(', ',$felhasznalo->getFelhasznaloFields()).'<br/>';
echo $br . $br;

$ugyfel_adatok = array(
   'azon'=>"{$r}",
   'cim'=>'Pelda utca 42.',
   'email'=>'pelda@pelda.hu',
   'telefon'=>'555555'
);
$ugyfel = new Ugyfel();
$ugyfel->create($ugyfel_adatok);
//$ugyfel=$pm->createObject('ugyfel',$ugyfel_adatok);


echo 'Az �j �gyf�l: ';

echo implode(', ',$ugyfel->getUgyfelFields()).'<br/>'; 

echo '<br/>';
echo '<br/><h3>Admin letrehozasa</h3>';
$r2=rand(1,150000);
$felhasznalo_adatok=array(
  'azon'=>"{$r2}",
  'jelszo'=>'jelszo'
);

$felhasznalo = new Felhasznalo();
$felhasznalo->create($felhasznalo_adatok);
//$felhasznalo=$pm->createObject('felhasznalo',$felhasznalo_adatok);

echo 'Az �j felhaszn�l�: ';

echo implode(', ',$felhasznalo->getFelhasznaloFields()).'<br/>';
$admin_adatok = array(
   'azon'=>"{$r2}",
   'jelszo'=>'jelszo2'
);
//$admin=$pm->createObject('admin',$admin_adatok);
$admin = new Admin();
$admin->create($admin_adatok);

echo 'Az �j Admin: ';

echo implode(', ',$admin->getAdminFields()).'<br/>'; 
?>
</body>
</html>