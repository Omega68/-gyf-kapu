<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Igenylesek_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Igenylesek_Site_Component": return $this->myfolder."/igenylesek_site_component.php";
            case "Igenyles" : return $this->myfolder."/igenyles.php";
            default:
                return null;
        }
    }
}