<?php
/**
 * Created by PhpStorm.
 * User: norbert
 * Date: 2015.05.08.
 * Time: 22:44
 */

class Urlap_errors {

    const EMPTY_NAME = "EMPTY_NAME";

    static function get_error_msg($code){
        switch ($code){
            case self::EMPTY_NAME: return "Üres";
        }

    }
}