<?php

namespace PGNChess\Tests\Unit\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Movetext as PgnFileMovetext;
use PHPUnit\Framework\TestCase;

class MovetextTest extends TestCase
{
    const PGN_FOLDER = __DIR__.'/data/movetext';

    /**
     * @dataProvider movetextData
     * @test
     */
    public function to_string($movetext, $string)
    {
        $movetextToString = (new PgnFileMovetext(self::PGN_FOLDER."/$movetext"))->toString();
        $string = preg_replace('~[[:cntrl:]]~', '', file_get_contents(self::PGN_FOLDER."/$string"));

        $this->assertEquals($movetextToString, $string);
    }

    public function movetextData()
    {
        $data = [];
        for ($i = 1; $i <= 2; ++$i) {
            $i <= 9
                ? $data[] = ["0$i-movetext.pgn", "0$i-string.pgn"]
                : $data[] = ["$i-movetext.pgn", "$i-string.pgn"];
        }

        return $data;
    }
}
