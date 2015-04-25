<?

class Site_Router{
  protected $get=array();
  protected $components_to_show=array(),$component_slots=array(),$default_menu=array(),$available_components=array();
  protected $site_name,$template_class;
  
  private $components_prepared=false;

  static function newInstance($site_name){
    $config_filename=__DIR__."/sites/".$site_name.".php";
    
    if (!file_exists($config_filename)) throw new Exception ('Site configuration not found: "'.$site_name.'"');
    $config= require $config_filename;
    return new self($site_name, $config['template_class'], $config['component_slots'],$config['components'],$config['default_menu']); 
  }

  final function __construct($site_name, $template_class, array $component_slots, array $components, array $default_menu=array()){
    $this->component_slots=$component_slots;
    $this->default_menu=$default_menu;
    $this->available_components=$components;
    $this->site_name=$site_name;
    $this->template_class=$template_class;
  }

  final protected function addComponent($slot,$component_name,$component_class,array $params=null){
    if (isset($this->component_slots[$slot])){
      if (!class_exists($component_class)) throw new Exception('Component class does not exist: "'.$component_class.'"');
      if (!is_subclass_of($component_class, "Site_Component")) throw new Exception('Component class not subclass of Site_Component: "'.$component_class.'"');
      $this->components_to_show[$slot]=new $component_class($component_name,$this->site_name,$params);
    }  
  }
  
  /**
  return array of Site_Component
  */
  final protected function prepareComponents(){
    if (!$this->components_prepared) {
      if (empty($_GET)) $_GET=$this->default_menu;
      $this->get=$_GET;
      
      //lekérdezzük a configban beállított komponenseket és a template komponenshelyeit
      $comps=$this->available_components;
      $slots=$this->component_slots;
  
      //Végigmegyünk a get-en, és az ott megadott komponenseket leellenőrizzük hogy beállíthatóak-e az adott slotra. Ha igen, akkor megjegyezzük a slothoz.
      foreach ($this->get as $slot_name=>$component_name) {
        if (isset($slots[$slot_name]) and isset($comps[$component_name]) and in_array($slot_name,$comps[$component_name]['allowed_slots'],true)) $slots[$slot_name]=$component_name;
      }
      //Végigmegyünk a slotokra meghatározott komponenseken és példányosítjuk őket.        
      foreach ($slots as $slot_name=>$component_name) {
        if (!empty($component_name)) $this->addComponent($slot_name,$component_name,$comps[$component_name]['class'],$comps[$component_name]['params']);    	
      }
          
      
      $this->components_prepared=true;
    }
    
    return $this->components_to_show; 
  }

  /**
  Minden aktív komponensre process()-t hív
  */
  final function processComponents(){
    $components=$this->prepareComponents();
    
    //Minden komponenst process-elünk (controller menet).
    foreach ($components as $component) {
      $component->process();
    }
  }
  
  /**
  Példányosítja és lefuttatja a megadott osztályú Site_Template-et
  */
  final function showSite(){
    $components=$this->prepareComponents();
    $template=new $this->template_class($components);
    $template->show();
  }

  /**
  processComponents(), aztán showSite()
  */
  final function run(){
    $this->processComponents();
    $this->showSite();    
  }

}
