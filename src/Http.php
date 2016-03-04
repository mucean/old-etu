<?php
namespace Etu;

class Http {
    private static $status = [
        200 => 'OK',
        404 => 'Not Fount'
    ];

    public static function getStatusMessage($status) {
        return isset(self::$status[$status]) ? self::$status[$status] : false;
    }
}
