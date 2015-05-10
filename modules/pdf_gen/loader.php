<?php

class PDF_Gen_Loader extends AbstractLoader{

    /**
     * return:
     * - annak a fájlnak a teljes minősített neve, amiben a megadott osztály található
     * - null, ha az osztálynév nem ismert
     */
    protected function getFileNameForClass($classname)
    {
        // protected function getFileNameForClass($classname){
        switch ($classname) {
            case "PDF_Gen" : return $this->myfolder."/pdf_gen.php";
            default:
                return null;
        }
    }
}