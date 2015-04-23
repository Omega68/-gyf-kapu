<?


abstract class API_Module{
  final function __construct(){}
  
  /**
  return array of string
  Ebben kell a keretrendszer felé megadni, hogy milyen nevű api hívásokat támogat a modul.
  A függvény nevét a ?module=függvényneve url paraméterben kell megadni. 
  */
  abstract function getSupportedFunctions();
  
  /**
  $function=$_GET['function']
  $params=$_GET
  $data=$_POST  
  
  A http választ a kimenetre kell írni, és headert beállítani
  A $function elfogadott értékeit a getSupportedFunctions metódusban kell visszaadni. 
  */
  abstract function handleRequest($function,array $params, array $data=null);
}
