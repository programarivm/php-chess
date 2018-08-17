<?php

namespace PGNChess\Tests\Integration\PGN\File;

use Dotenv\Dotenv;
use PGNChess\Db\MySql;
use PGNChess\PGN\File\ToMySql as PgnFileToMySql;
use PHPUnit\Framework\TestCase;

class ToMySqlTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data';

    public static function setUpBeforeClass()
    {
        $dotenv = new Dotenv(__DIR__.'/../../../../');
        $dotenv->load();

        if (getenv('APP_ENV') !== 'dev') {
            echo 'The integration tests can run on dev environment only.' . PHP_EOL;
            exit;
        }
    }

    public function tearDown()
    {
        MySql::getInstance()->query('DELETE from games');
    }

    /**
     * @dataProvider pgnData
     * @test
     */
    public function convert_games($filename)
    {
        $sql = (new PgnFileToMySql(self::PGN_FOLDER."/$filename"))->convert();
        $result = MySql::getInstance()->query($sql);

        $this->assertNotEquals(false, $result);
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
