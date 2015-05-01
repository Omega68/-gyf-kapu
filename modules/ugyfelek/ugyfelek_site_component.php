<?php
/**
 * Created by PhpStorm.
 * User: nor
 * Date: 2015.04.25.
 * Time: 8:39
 */

class Ugyfelek_Site_Component extends Site_Component{
    function process(){
    }

    function show(){
        ?><h2>Új ügyfél regisztrálása</h2>

        <form action="?page=ugyfel">
            <input type="submit" value="Submit">
        </form>

        <?


        ?><h2>Ügyfelek</h2><?

        $pm = PersistenceManager::getInstance();
        $ugyfelek = $pm->getAllObjects("Ugyfel");
        foreach($ugyfelek as $u){
            echo "<p>". $u->to_string() ."</p>";
        }

    }
}