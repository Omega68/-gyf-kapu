<?
/*

API hívás példa:

erp.fejlesztesgyak2014.info/api.php?module=teszt&function=getTesztAdatok&id=123
*/

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE & ~E_WARNING & ~E_DEPRECATED);

require_once __DIR__."/loader/loader_main.php";

session_start();

$router=new API_Router($_GET,$_POST);
$router->run();

