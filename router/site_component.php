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

  protected  function validationError($errors){
      if(count($errors) > 0 ){
          echo "<p style=\"color: red\">Hiba: ";
          foreach( $errors as $e )
              echo Error::get_error_msg($e[0]) . " Mező: " . $e[1] . "<br/>";
          echo "</p>";
          return true;
      }

      return false;
  }


  }
