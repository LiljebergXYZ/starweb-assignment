<?php

namespace Nano;

use App\Config;

/**
 * Database class
 */
class Database {

    /**
     * Database instance
     *
     * @var \PDO
     */
    private static $_instance;

    /**
     * Sets and gets the current database instance
     *
     * @return \PDO
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            $pdo_options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
            $pdo_options[\PDO::ATTR_DEFAULT_FETCH_MODE] = \PDO::FETCH_OBJ;
            self::$_instance = new \PDO(sprintf('mysql:host=%s;dbname=%s', Config::DB_HOST, Config::DB_NAME), Config::DB_USER, Config::DB_PASSWORD, $pdo_options);
        }
        return self::$_instance;
    }

}