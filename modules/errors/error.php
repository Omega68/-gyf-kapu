<?php

class Error {

    const EMPTY_FIELD = "EMPTY_FIELD";
    const MANDATORY = "MANDATORY";
    const SHORT_PASSWORD = "SHORT_PASSWORD";

    public static function get_error_msg($code){
        switch ($code){
            case self::MANDATORY: return "Kötelező mező! ";
            case self::EMPTY_FIELD: return "Üres mező! ";
            case self::SHORT_PASSWORD: return "Rövid jelszó! ";
        }

    }

}

?>