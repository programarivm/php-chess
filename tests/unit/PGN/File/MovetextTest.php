<?php

namespace PGNChess\Tests\Unit\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Movetext as PgnFileMovetext;
use PHPUnit\Framework\TestCase;

class MovetextTest extends TestCase
{
    const DATA_FOLDER = __DIR__.'/data';

    /**
     * @dataProvider movetextData
     * @test
     */
    public function to_string($pgn, $txt)
    {
        $movetextToTxt = (new PgnFileMovetext(self::DATA_FOLDER."/$pgn"))->toString();
        $string = preg_replace('~[[:cntrl:]]~', '', file_get_contents(self::DATA_FOLDER."/$txt"));

        $this->assertEquals($movetextToTxt, $string);
    }

    public function movetextData()
    {
        return [
            ['movetext-01.pgn', 'movetext-01.txt'],
            ['movetext-02.pgn', 'movetext-02.txt'],
            ['movetext-03.pgn', 'movetext-03.txt'],
        ];
    }
}
