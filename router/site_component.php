<?

abstract class Site_Component{

  protected $component_name,$site_name,$params=array();
  
  final function __construct($component_name,$site_name,array $params=null){
    $this->params=(array)$params;
    $this->site_name=$site_name;
    $this->component_name=$component_name;
    $this->afterConstruction();
  } 

  /**
  Konstrukció utáni saját inicializálást végezhetsz ebben a metódusban.
  */
  protected function afterConstruction(){
  }

  abstract function process();
  
  abstract function show();
   
}
