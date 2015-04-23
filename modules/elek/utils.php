<?

class Pelda_Utils{

  static function url_atiro($get,$uj_mezok=array()){
    $url="";
    if (!is_array($get)) $get=array();
    if (!is_array($uj_mezok)) $uj_mezok=array();
    $get=array_merge($get, $uj_mezok);
    foreach ($get as $key=>$value) {
  	  if ($value!==null){
  	    if (!empty($url)) $url.="&";
        $url.=($key."=".$value);
      }
    }
    
    if (!empty($url)) $url="?".$url;
    
    return $url;
  }


}

?>