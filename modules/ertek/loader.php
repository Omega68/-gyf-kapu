<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.10.
 * Time: 18:38
 */

class Ertek_Loader extends AbstractLoader{
    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Ertek_Site_Component": return $this->myfolder."/ertek_site_component.php";
            case "Ertek" : return $this->myfolder."/ertek.php";
            default:
                return null;
        }
    }
}