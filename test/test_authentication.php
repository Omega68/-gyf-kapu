<html>
<head><title>ugyfel teszt</title></head>
<body>
<h1>Teszt</h1>
<?php
require_once("../persistence_manager.php");
require_once("../ugyfel.php");
require_once("../felhasznalo.php");
require_once("../admin.php");
require_once("../session.php");
$auth=new Authentication();
echo $auth->isLogged()."<br>";
$user=$auth->login('125748','jelszo');
echo $auth->whoLoggedIn()."<br>";
$user=$auth->login('125712233423','jelszo');
$user=$auth->login('adsadjsf','ajdhfa');
echo $auth->whoLoggedIn()."<br>";
echo $auth->isLogged()."<br>";
$auth->logout();
echo $auth->whoLoggedIn()."<br>";
echo "<br>";
echo "Masodik kor"."<br>";
echo $auth->isLogged()."<br>";
echo $auth->whoLoggedIn()."<br>";
echo "Login felhasznalo: azon: 125748, jelszo: jelszo <br>";
$user=$auth->login('125748','jelszo');
echo "ki van bent:".$auth->whoLoggedIn()."<br>";
$auth->logout();
?>
</body>
</html>