<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Feliratkozas_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Feliratkozas_Site_Component": return $this->myfolder."/feliratkozas_site_component.php";
            case "Feliratkozas" : return $this->myfolder."/feliratkozas.php";
            default:
                return null;
        }
    }
}