<?php

namespace Source\Framework\Core;

class Transaction
{
    private static $conn;

    public static function open()
    {
        //print_r("TRANSACTION OPEN\n");
        self::$conn = Connect::getInstance(false);
        self::$conn->beginTransaction();
    }

    public static function rollback()
    {
        if (self::$conn) {
            //print_r("ROLLBACK\n\n");
            self::$conn->rollBack();
        }
    }

    public static function get()
    {
        if (self::$conn) {
            return self::$conn;
        }

        return null;
    }

    public static function close()
    {
        if (self::$conn) {
            //print_r("CLOSE AND COMMIT\n\n");
            self::$conn->commit();
            self::$conn = null;
        }
    }
}
