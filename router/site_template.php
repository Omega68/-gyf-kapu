<?

abstract class Site_Template {
  private $components=array();
  
  /**
  $components=array of Site_Component
  */
  final function __construct(array $components=null){
    $this->components=(array)$components;
  }

  final protected function show_slot($slot_name){
    if (isset($this->components[$slot_name])) $this->components[$slot_name]->show();
  }
  
  //Ide kell megírni a ténylegesen megjelenő weboldalt.
  abstract function show();
  
}
