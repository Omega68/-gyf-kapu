<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.27.
 * Time: 19:49
 */

class Perzisztencia_Loader extends AbstractLoader
{
    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "PersistenceManager":
                return $this->myfolder . "/persistence_manager.php";
            case "Persistent" :
                return $this->myfolder . "/persistent.php";
            default:
                return null;
        }
    }
}