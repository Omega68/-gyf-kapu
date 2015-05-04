<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class Ugyfelek_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Ugyfelek_Site_Component": return $this->myfolder."/ugyfelek_site_component.php";
            case "ERP_Ugyfelek_Site_Component": return $this->myfolder."/erp_ugyfelek_site_component.php";
            case "Ugyfel" : return $this->myfolder."/ugyfel.php";
            default:
                return null;
        }
    }
}