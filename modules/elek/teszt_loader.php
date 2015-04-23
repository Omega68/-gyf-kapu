<?

class Teszt_Loader extends AbstractLoader{
  
  protected function getFileNameForClass($classname){
    switch ($classname) {
      case "Teszt_Komponens": return $this->myfolder."/teszt_komponens.php";
      case "Pelda_Utils": return $this->myfolder."/utils.php";
      case "Teszt_API_Modul": return $this->myfolder."/teszt_api_modul.php";
    }
  } 
  
}
