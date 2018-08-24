<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Db\Pdo;
use PGNChess\Exception\PgnFileSyntaxException;
use PGNChess\PGN\File\Convert as PgnFileConvert;
use PHPUnit\Framework\TestCase;

class ConvertTest extends TestCase
{
    const DATA_FOLDER = __DIR__.'/../../data';

    public static function setUpBeforeClass()
    {
        if ($_ENV['APP_ENV'] !== 'test') {
            echo 'The integration tests can run on test environment only.' . PHP_EOL;
            exit;
        }
    }

    /**
     * @dataProvider pgnData
     * @test
     */
    public function to_mysql_games($filename)
    {
        $sql = (new PgnFileConvert(self::DATA_FOLDER."/$filename"))->toMySqlScript();

        $this->assertTrue(strpos($sql, 'INSERT INTO games') === 0);
    }

    public function pgnData()
    {
        return [
            ['01-games.pgn']
        ];
    }
}
