<?php

namespace PGNChess\Tests\Integration\PGN\File;

use Dotenv\Dotenv;
use PGNChess\Db\MySql;
use PGNChess\PGN\File\ToMySql as PgnFileToMySql;
use PHPUnit\Framework\TestCase;

class ToMySqlTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data';

    public function __construct()
    {
        $dotenv = new Dotenv(__DIR__.'/../../../../');
        $dotenv->load();
    }

    /**
     * @test
     */
    public function convert_01_games()
    {
        $sql = (new PgnFileToMySql(self::PGN_FOLDER.'/01_games.pgn'))->convert();
        $result = MySql::getInstance()->query($sql);

        $this->assertNotEquals(false, $result);
    }
}
