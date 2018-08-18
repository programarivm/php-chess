<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Syntax as PgnFileSyntax;
use PHPUnit\Framework\TestCase;

class SyntaxTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data';

    /**
     * @dataProvider pgnNonStrGames
     * @test
     */
    public function check_non_str_games($filename, $invalid)
    {
        $result = (new PgnFileSyntax(self::PGN_FOLDER."/$filename"))->check();

        $this->assertEquals($invalid, count($result));
    }

    public function pgnNonStrGames()
    {
        return [
            ['01-non-str-games.pgn', 8],
            ['02-non-str-games.pgn', 17],
            ['03-non-str-games.pgn', 15],
        ];
    }
}
