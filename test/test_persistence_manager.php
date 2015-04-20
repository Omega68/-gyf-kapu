<html>
<head><title>ugyfel teszt</title></head>
<body>
<h1>Teszt</h1>
<?php
require_once("../persistence_manager.php");
require_once("../model/ugyfel.php");
require_once("../model/felhasznalo.php");
require_once("../model/admin.php");

$pm = PersistenceManager::getInstance();
$felhasznalo_adatok=array(
    'jelszo'=>'jelszo'
);
$felhasznalo=$pm->getObjectsByField('felhasznalo',$felhasznalo_adatok);
echo "Talalt felhasznalok:".count($felhasznalo).PHP_EOL;
foreach($felhasznalo as $key => $value){
    echo "Felhasznalo:".$value->getFelhasznaloFields()['azon'].PHP_EOL;
}
echo "<br>";
echo "<br>";
$pm=PersistenceManager::getInstance();
$ugyfel_adatok=array(
  'telefon'=>'555555'
);
$ugyfel=$pm->getObjectsByField('ugyfel',$ugyfel_adatok);
echo "Talalt ugyfel:".count($ugyfel).PHP_EOL;
foreach($ugyfel as $key => $value){
    echo "Ugyfel:".$value->getUgyfelFields()['azon'].PHP_EOL;
}

echo "<br>";
echo "<br>";
$pm=PersistenceManager::getInstance();
$ugyfel_adatok=array(
    'telefon'=>'5552342'
);
$ugyfel=$pm->getObjectsByField('ugyfel',$ugyfel_adatok);
echo "Talalt ugyfel:".count($ugyfel).PHP_EOL;
foreach($ugyfel as $key => $value){
    echo "Ugyfel:".$value->getUgyfelFields()['azon'].PHP_EOL;
}

echo "<br>";
echo "<br>";
$pm=PersistenceManager::getInstance();
$ugyfel_adatok=array(
    'cim'=>'Pelda utca 42.'
);
$ugyfel=$pm->getObjectsByField('ugyfel',$ugyfel_adatok);
echo "Talalt ugyfel:".count($ugyfel).PHP_EOL;
foreach($ugyfel as $key => $value){
    echo "Ugyfel:".$value->getUgyfelFields()['azon'].PHP_EOL;
}
?>
</body>
</html>