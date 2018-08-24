<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Db\Pdo;
use PGNChess\Exception\PgnFileSyntaxException;
use PGNChess\PGN\File\Seed as PgnFileSeed;
use PHPUnit\Framework\TestCase;

class SeedTest extends TestCase
{
    const DATA_FOLDER = __DIR__.'/../../data';

    public static function setUpBeforeClass()
    {
        if ($_ENV['APP_ENV'] !== 'test') {
            echo 'The integration tests can run on test environment only.' . PHP_EOL;
            exit;
        }

        Pdo::getInstance()->query('TRUNCATE TABLE games');
    }

    /**
     * @dataProvider pgnData
     * @test
     */
    public function db($filename)
    {
        (new PgnFileSeed(self::DATA_FOLDER."/$filename"))->db();

        $result = Pdo::getInstance()->query('SELECT count(*) as count FROM games')->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals(512, $result['count']);
    }

    public function pgnData()
    {
        return [
            ['01-games.pgn']
        ];
    }
}
