<?php

class Database
{
    private const DSN = "mysql:host=localhost;dbname=bookstore";
    private const username = "root";
    private const password = "";

    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {

                self::$connection = new PDO(self::DSN, self::username, self::password);

                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("database Connection Failed: {$e->getMessage()}");
            }
        }

        return self::$connection;
    }
}
