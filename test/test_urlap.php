<html>
<head><title>Urlap teszt</title></head>
<body>
<h1>Teszt</h1>
<?php
require_once("../persistence_manager.php");
require_once("../model/kitoltott_mezo.php");
require_once("../model/mezo.php");
require_once("../model/urlap_sablon.php");
require_once("../model/igenyles.php");

$pm = PersistenceManager::getInstance();

$r=rand(1,150000); 

$mezo_adatok=array(
  'azon' => "{$r}",
  'tipus' => 'jelszo',
  'kotelezoseg' => 'i',
  'sablon_azon' => '3542424'
);

//$mezo=$pm->createObject('mezo',$mezo_adatok);

//echo 'Az új mezõ: ';

//echo implode(', ',$mezo->getMezoFields()).'<br/>'; 



$urlap_adatok=array(
  'azon' => "{$r}",
  'letrehozas_datuma' => '2014',
  'allapot' => 'aktiv',
  'admin_azon' => 'a365424'
);

$urlaper = new UrlapSablon();
$urlaper->create($urlap_adatok);

echo 'Az új ûrlapsablon: ';

echo implode(', ',$urlaper->getUrlapSablonFields()).'<br/>'; 

/*
$mezo_adatok2=array(
  'azon' => "{$r}",
  'tipus' => 'kerekites',
  'kotelezoseg' => 'i',
);

$mezok=$urlaper->createMezo($mezo_adatok2);

echo 'Az új mezõ a sablonhoz: ';

echo implode(', ',$mezok->getMezoFields()).'<br/>'; 
*/

?>
</body>
</html>