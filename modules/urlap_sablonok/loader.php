<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Urlap_sablonok_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Urlap_sablonok_Site_Component": return $this->myfolder."/urlap_sablonok_site_component.php";
            case "UrlapSablon" : return $this->myfolder."/urlap_sablon.php";
            default:
                return null;
        }
    }
}