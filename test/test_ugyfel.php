<html>
<head><title>Ügyfél teszt</title></head>
<body>
<h1>Teszt</h1>
<?php
require_once("../persistence_manager.php");
require_once("../ugyfel.php");
require_once("../felhasznalo.php");
require_once("../admin.php");

$pm = PersistenceManager::getInstance();

$r=rand(1,150000); 

$felhasznalo_adatok=array(
  'azon' => "{$r}",
  'jelszo' => 'jelszo'
);

$felhasznalo=$pm->createObject('felhasznalo',$felhasznalo_adatok);

echo 'Az új felhasználó: ';

echo implode(', ',$felhasznalo->getFelhasznaloFields()).'<br/>'; 

$ugyfel_adatok = array(
   'azon'=>"{$r}",
   'cim'=>'Pelda utca 42.',
   'email'=>'pelda@pelda.hu',
   'telefon'=>'555555'
);
$ugyfel=$pm->createObject('ugyfel',$ugyfel_adatok);


echo 'Az új ügyfél: ';

echo implode(', ',$ugyfel->getUgyfelFields()).'<br/>'; 

echo '<br/>';
echo '<br/><h3>Admin letrehozasa</h3>';
$r2=rand(1,150000);
$felhasznalo_adatok=array(
  'azon'=>"{$r2}",
  'jelszo'=>'jelszo'
);

$felhasznalo=$pm->createObject('felhasznalo',$felhasznalo_adatok);

echo 'Az új felhasználó: ';

echo implode(', ',$felhasznalo->getFelhasznaloFields()).'<br/>';
$admin_adatok = array(
   'azon'=>"{$r2}"
);
$admin=$pm->createObject('admin',$admin_adatok);


echo 'Az új Admin: ';

echo implode(', ',$admin->getAdminFields()).'<br/>'; 
?>
</body>
</html>