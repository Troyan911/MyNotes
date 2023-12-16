<?php

namespace Core;

use PDO;

class Db
{
    protected static PDO|null $instance = null;

    public static function getInstance(): \PDO
    {
        if (is_null(static::$instance)) {
            $host = Config::get("db.host");
            $name = Config::get("db.name");
            $user = Config::get("db.user");
            $pass = Config::get("db.pass");

            $dsn = "mysql:host=$host;dbname=$name";
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            static::$instance = new PDO(
                $dsn,
                $user,
                $pass,
                $options
            );

        }
        return static::$instance;
    }
}