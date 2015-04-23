<?

class Admin_Site_Loader extends AbstractLoader{
  
  protected function getFileNameForClass($classname){
    if ($classname==="Admin_Login_Site_Template") return $this->myfolder."/templates/login_template.php"; 
    if ($classname==="Admin_Template") return $this->myfolder."/templates/admin_template.php";
    if ($classname==="Teszt_Authenticator") return $this->myfolder."/teszt_authenticator.php";
  } 
}
