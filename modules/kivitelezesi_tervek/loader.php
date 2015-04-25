<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Kivitelezesi_tervek_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Kivitelezesi_tervek_Site_Component": return $this->myfolder."/kivitelezesi_tervek_site_component.php";
            case "KivitelezesiTerv" : return $this->myfolder."/kivitelezesi_terv.php";
            default:
                return null;
        }
    }
}