<?php

namespace PGNChess\Tests\Unit\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Validate as PgnFileValidate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data/non-str-games';

    /**
     * @dataProvider pgnNonStrGames
     * @test
     */
    public function syntax_non_str_games($filename, $invalid)
    {
        $result = (new PgnFileValidate(self::PGN_FOLDER."/$filename"))->syntax();

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
