<?php

namespace PGNChess\Db;

/**
 * Pdo class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Pdo
{
    /**
     * Reference to the Pdo instance.
     *
     * @var \PGNChess\Db\Pdo
     */
    private static $instance;

    /**
     * DSN.
     *
     * @var string
     */
    private $dsn;

    /**
     * PDO handler.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Returns the current instance.
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
        $this->dsn = getenv('DB_DRIVER') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
        $this->pdo = new \PDO(
            $this->dsn,
            getenv('DB_USER'),
            getenv('DB_PASSWORD')
        );
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Prevents from cloning.
     */
    private function __clone()
    {
    }

    /**
     * Prevents from unserializing.
     */
    private function __wakeup()
    {
    }

    /**
     * Queries the database.
     *
     * @param string
     * @param array
     * @return bool
     */
    public function query($sql, $values = [])
    {
        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $value) {
            $stmt->bindValue(
                $value['parameter'],
                $value['value'],
                $value['type'] ?? null
            );
        }

        $stmt->execute();
    }
}
