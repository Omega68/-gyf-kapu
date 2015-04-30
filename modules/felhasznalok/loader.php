<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Felhasznalok_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Felhasznalok_Site_Component": return $this->myfolder."/felhasznolok_site_component.php";
            case "Felhasznalo" : return $this->myfolder."/felhasznalo.php";
            default:
                return null;
        }
    }
}