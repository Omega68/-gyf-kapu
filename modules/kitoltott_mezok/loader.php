<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Kitoltott_mezok_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Kitoltott_mezok_Site_Component": return $this->myfolder."/kitoltott_mezok_site_component.php";
            case "KitoltottMezo" : return $this->myfolder."/kitoltott_mezo.php";
            default:
                return null;
        }
    }
}