<?

class Uzenetek_Loader extends AbstractLoader{
  
  protected function getFileNameForClass($classname){
    switch ($classname) {
      case "Uzenetek_Site_Component": return $this->myfolder."/uzenetek_site_component.php";
    }
  } 
  
}
