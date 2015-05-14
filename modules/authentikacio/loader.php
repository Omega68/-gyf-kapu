<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 10:28
 */

class Authentikacio_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "Authentication": return $this->myfolder."/session.php";
            case "Authentication_Site_Component": return $this->myfolder."/authentication_site_component.php";
            case "Registration_Site_Component": return $this->myfolder."/reg_site_component.php";
            case "TestData": return $this->myfolder."/testdata.php";
            default:
                return null;
        }
    }

}