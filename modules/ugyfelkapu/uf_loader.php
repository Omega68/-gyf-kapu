<?

class Teszt_Loader extends AbstractLoader{
  
  protected function getFileNameForClass($classname){
    switch ($classname) {
      case "Admin": return $this->myfolder."model/admin.php";
      case "Felhasznalo": return $this->myfolder."model/felhasznalo.php";
      case "Igenyles": return $this->myfolder."model/igenyles.php";
      case "KitoltottMezo": return $this->myfolder."model/kitoltott_mezo.php";
      case "KivitelezesiTerv": return $this->myfolder."model/kivitelezesi_terv.php";
      case "Mezo": return $this->myfolder."model/mezo.php";
      case "Ugyfel": return $this->myfolder."model/ugyfel.php";
      case "UrlapSablon": return $this->myfolder."model/urlap_sablon.php";
    }
  } 
  
}
