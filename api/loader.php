<?

class API_Loader extends AbstractLoader{
  
  protected function getFileNameForClass($classname){
    switch ($classname) {
      case "API_Module": return $this->myfolder."/api_module.php";
      case "API_Router": return $this->myfolder."/api_router.php";
    }
  } 
  
}
