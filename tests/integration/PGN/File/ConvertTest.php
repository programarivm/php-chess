<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Db\Pdo;
use PGNChess\Exception\InvalidPgnFileSyntaxException;
use PGNChess\PGN\File\Convert as PgnFileConvert;
use PHPUnit\Framework\TestCase;

class ConvertTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data';

    public static function setUpBeforeClass()
    {
        if ($_ENV['APP_ENV'] !== 'test') {
            echo 'The integration tests can run on test environment only.' . PHP_EOL;
            exit;
        }
    }

    public function tearDown()
    {
        Pdo::getInstance()->query('TRUNCATE TABLE games');
    }

    /**
     * @dataProvider pgnData
     * @test
     */
    public function to_mysql_games($filename)
    {
        $sql = (new PgnFileConvert(self::PGN_FOLDER."/$filename"))->toMySqlScript();
    }

    public function pgnData()
    {
        $data = [];
        for ($i = 1; $i <= 10; ++$i) {
            $i <= 9 ? $data[] = ["0$i-games.pgn"] : $data[] = ["$i-games.pgn"];
        }

        return $data;
    }
}
