<?

define("APPLICATIONROOT",realpath(__DIR__."/../"));
define("LOADERROOT",APPLICATIONROOT);

require_once __DIR__."/abstract_loader.php";
$loaders_config=require_once __DIR__."/loaders_config.php";

foreach ($loaders_config as $loader_classname=>$loader_file) {
  $loader_path=LOADERROOT.$loader_file;
  require_once $loader_path; 
  $loader=new $loader_classname(APPLICATIONROOT,APPLICATIONROOT,dirname($loader_path));
  spl_autoload_register(array($loader,"loadClass"));
}
