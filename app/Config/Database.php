<?php

require_once __DIR__ . '/config.php';

class Database
{
    private static $connection = null;

    public static function connect()
    {
        if (self::$connection === null) {

            self::$connection = new mysqli(
                env('DB_HOST'),
                env('DB_USER'),
                env('DB_PASSWORD'),
                env('DB_NAME'),
                (int) env('DB_PORT')
            );

            if (self::$connection->connect_error) {

                die(
                    "Database Connection Failed: "
                    . self::$connection->connect_error
                );
            }

            self::$connection->set_charset('utf8mb4');
        }

        return self::$connection;
    }
}