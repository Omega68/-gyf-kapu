<?

class Protected_Site_Router{
  protected $login_site_name,$protected_site_name;
  protected $sites=array(),$authenticator;
  
  
  function __construct($protected_site_name,$login_site_name, Site_Authenticator $authenticator){
    $this->login_site_name=$login_site_name;
    $this->protected_site_name=$protected_site_name;
    $this->authenticator=$authenticator;
  }
  
  final function getSite($site_name){
    if (!isset($this->sites[$site_name])){
      $this->sites[$site_name]=Site_Router::newInstance($site_name);
    }
    
    return $this->sites[$site_name];
  }
  
  final function run(){
    //Controller menet
    if ($this->authenticator->isUserAuthorized()){
      $router=$this->getSite($this->protected_site_name);
      $router->processComponents();
    } else {
      $router=$this->getSite($this->login_site_name);
      $router->processComponents();
    }
    
    
    //View menet
    if ($this->authenticator->isUserAuthorized()){
      $router=$this->getSite($this->protected_site_name);
      $router->showSite();
    } else {
      $router=$this->getSite($this->login_site_name);
      $router->showSite();
    }
  } 

}
