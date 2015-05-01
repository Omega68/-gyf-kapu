<?php
/**
 * Created by PhpStorm.
 * User: Kinga
 * Date: 2015.05.01.
 * Time: 10:47
 */

class FelhasznaloErrors {

    const EMPTY_NAME = "EMPTY_NAME";

    static function get_error_msg($code){
        switch ($code){
            case self::EMPTY_NAME: return "Üres";
        }

    }
}