<?

class Core_Loader extends AbstractLoader{
  
  protected function getFileNameForClass($classname){
    switch ($classname) {
      case "Site_Router": return $this->myfolder."/router.php"; 
      case "Site_Template": return $this->myfolder."/site_template.php"; 
      case "Site_Component": return $this->myfolder."/site_component.php";
      case "Site_Authenticator": return $this->myfolder."/site_authenticator.php";
      case "Protected_Site_Router": return $this->myfolder."/protected_site_router.php"; 
    }
  } 
  
}
