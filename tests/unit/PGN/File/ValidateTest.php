<?php

namespace PGNChess\Tests\Unit\PGN\File;

use PGNChess\PGN\File\Validate as PgnFileValidate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    const DATA_FOLDER = __DIR__.'/data';

    /**
     * @dataProvider gamesWithThreeInvalidData
     * @test
     */
    public function syntax_games_with_three_invalid($filename)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/$filename"))->syntax();

        $this->assertTrue($result->valid > 0);
        $this->assertEquals(3, count($result->errors));
    }

    public function gamesWithThreeInvalidData()
    {
        return [
            ['games-01-with-three-invalid.pgn'],
        ];
    }

    /**
     * @dataProvider gamesData
     * @test
     */
    public function syntax_games($filename)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/$filename"))->syntax();

        $this->assertTrue($result->valid > 0);
        $this->assertTrue(!isset($result->errors));
    }

    public function gamesData()
    {
        return [
            ['games-01.pgn'],
            ['games-02.pgn'],
            ['games-03.pgn'],
        ];
    }

    /**
     * @dataProvider nonStrGamesData
     * @test
     */
    public function syntax_non_str_games($filename, $invalid)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/$filename"))->syntax();

        $this->assertEquals($invalid, count($result->errors));
    }

    public function nonStrGamesData()
    {
        return [
            ['non-str-games-01.pgn', 8],
            ['non-str-games-02.pgn', 17],
            ['non-str-games-03.pgn', 15],
        ];
    }

    /**
     * @dataProvider textData
     * @test
     */
    public function syntax_text($filename)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/$filename"))->syntax();

        $this->assertEquals(0, $result->valid);
        $this->assertTrue(!isset($result->errors));
    }

    public function textData()
    {
        return [
            ['text-01.pgn'],
            ['text-02.pgn'],
            ['text-03.pgn'],
        ];
    }

    /**
     * @dataProvider textWithNonStrGamesData
     * @test
     */
    public function syntax_text_with_non_str_games($filename, $nErrors)
    {
        $result = (new PgnFileValidate(self::DATA_FOLDER."/$filename"))->syntax();

        $this->assertEquals(0, $result->valid);
        $this->assertEquals($nErrors, count($result->errors));
    }

    public function textWithNonStrGamesData()
    {
        return [
            ['text-with-non-str-games-01.pgn', 1],
            ['text-with-non-str-games-02.pgn', 4],
            ['text-with-non-str-games-03.pgn', 2],
        ];
    }
}
