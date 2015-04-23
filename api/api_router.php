<?

class API_Router {
  private $get,$post;
  
  function __construct($get,$post){
    $this->get=$get;
    $this->post=$post;    
  }

  function run(){
    $res=$this->routeRequest();
    
    if ($res!==true){
      http_response_code(500);
      header('Content-Type: text/html; charset=utf-8');
      echo $res;      
    }
  }
  
  protected function routeRequest(){
    if (empty($this->get['module'])){
      return 'Nincs api modul kiváasztva: "module" paraméter üres';
    }
  
    $config=require_once __DIR__.'/api_config.php';
    
    if (!isset($config[$this->get['module']])){
      return 'Ismeretlen api modul azonosító: "'.$this->get['module'].'"';
    }
    
    $api_module_class=$config[$this->get['module']];
    
    if (!class_exists($api_module_class)) {
      return 'A megadott api modul osztály nem található: "'.$api_module_class.'"';
    }  
    
    if (!is_subclass_of($api_module_class, 'API_Module')) {
      return 'A megadott api modul osztály nem API_Module alosztály: "'.$api_module_class.'"';
    }
    
    $api_module=new $api_module_class;
    
    $params=$this->get;
    unset($params['function']);
    $function = $this->get['function'];
    
    
    if (!in_array($function, $api_module->getSupportedFunctions(), true)){
      return 'A megadott api modul nem támogatja ezt a függvényt: "'.$function.'"';
    }
    
    $api_module->handleRequest($function,$params,$this->post);
    
    return true;       
  }
}