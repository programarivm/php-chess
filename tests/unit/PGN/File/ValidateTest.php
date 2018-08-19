<?php

namespace PGNChess\Tests\Unit\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Validate as PgnFileValidate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data';

    /**
     * @dataProvider pgnNonStrGamesData
     * @test
     */
    public function syntax_non_str_games($filename, $invalid)
    {
        $result = (new PgnFileValidate(self::PGN_FOLDER."/non-str-games/$filename"))->syntax();

        $this->assertEquals($invalid, count($result->errors));
    }

    public function pgnNonStrGamesData()
    {
        return [
            ['01-non-str-games.pgn', 8],
            ['02-non-str-games.pgn', 17],
            ['03-non-str-games.pgn', 15],
        ];
    }

    /**
     * @dataProvider textData
     * @test
     */
    public function syntax_text($filename)
    {
        $result = (new PgnFileValidate(self::PGN_FOLDER."/text/$filename"))->syntax();

        $this->assertEquals(0, $result->valid);
        $this->assertEquals(0, count($result->errors));
    }

    public function textData()
    {
        return [
            ['01-text.pgn'],
            ['02-text.pgn'],
            ['03-text.pgn'],
        ];
    }
}
