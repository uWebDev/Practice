<?php

namespace App;


/**
 * Class Database
 * @package App
 */
class Database extends \PDO
{

    /**
     * Database constructor.
     */
    public function __construct()
    {
        try {
            parent::__construct(DB_CONNECTION, DB_USER, DB_PASSWORD, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                \PDO::MYSQL_ATTR_LOCAL_INFILE => true
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("The connection failed: [{$e->getMessage()}]");
        }
    }
}