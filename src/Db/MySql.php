<?php

namespace PGNChess\Db;

/**
 * MySql class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class MySql
{
    /**
     * Reference to the MySql instance.
     *
     * @var DB
     */
    private static $instance;

    /**
     * Db handler.
     *
     * @var mysqli
     */
    private $mysqli;

    /**
     * Returns the MySql instance.
     *
     * @return Singleton
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Constructor.
     */
    protected function __construct()
    {
        $this->mysqli = new \MySQLI(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME'),
            getenv('DB_PORT')
        );

        $this->mysqli->set_charset('utf8');
    }

    /**
     * Prevents cloning the MySql instance.
     */
    private function __clone()
    {
    }

    /**
     * Prevents unserializing the MySql instance.
     */
    private function __wakeup()
    {
    }

    /**
     * Queries the database.
     *
     * @param string The query
     * @return false|mysqli_result The result of the query
     */
    public function query($sql)
    {
        return $this->mysqli->query(
            $this->escape($sql)
        );
    }

    /**
     * Escapes the data to prevent sql injections.
     *
     * @param string The string to be escaped
     * @return string The escaped string
     */
    private function escape($data)
    {
        return $this->mysqli->real_escape_string($data);
    }
}
