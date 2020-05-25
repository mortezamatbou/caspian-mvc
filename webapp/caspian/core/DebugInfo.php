<?php

namespace Caspian\Core\Events;

class DebugInfo {

    static $enable = FALSE;
    static $message = array();

    public static function isEnable() {
        return self::$enable;
    }

    public static function getMessage() {
        return self::$message;
    }

    public static function setMessage($message) {
        self::$message[] = $message;
    }

}
