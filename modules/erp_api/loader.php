<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:23
 */

class ERP_API_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "ERP_API" : return $this->myfolder."/erp_api.php";
            default:
                return null;
        }
    }
}