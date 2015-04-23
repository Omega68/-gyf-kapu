<?

abstract class AbstractLoader{
  protected
    /**
    Az alkalmazás gyökér könyvtárának teljes útvonala 
    */           
    $app_folder
    
    /**
    A /modules könyvtárnak a teljes útvonala 
    */           
    ,$modules_folder
    
    /**
    Ennek a konkrét loader alosztálynak a könyvtára
    */
    ,$myfolder;
  
  final function __construct($app_folder,$modules_folder,$loader_folder){
    $this->app_folder=$app_folder;
    $this->modules_folder=$modules_folder;
    $this->myfolder=$loader_folder;
  }
  
  final function loadClass($classname){
    $path=$this->getFileNameForClass($classname);

    if (isset($path)) require_once $path;
  }
  
  /**
  return: 
    - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
    - null, ha az osztálynév nem ismert 
  */
  abstract protected function getFileNameForClass($classname); 
} 
