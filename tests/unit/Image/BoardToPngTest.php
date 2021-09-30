<?php

namespace Chess\Tests\Unit\Image;

use Chess\Board;
use Chess\FEN\StringToBoard;
use Chess\Image\BoardToPng;
use Chess\Tests\AbstractUnitTestCase;

class BoardToPngTest extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../output';

    public static function tearDownAfterClass(): void
    {
        unlink(self::OUTPUT_FOLDER . '/tmp.png');
    }

    /**
     * @test
     */
    public function output_00_starting()
    {
        $board = new Board();

        (new BoardToPng($board))->output(self::OUTPUT_FOLDER . '/tmp.png');

        $this->assertEquals(
            md5(file_get_contents(self::OUTPUT_FOLDER . '/tmp.png')),
            md5(file_get_contents(self::DATA_FOLDER . '/img/00_starting.png'))
        );
    }

    /**
     * @test
     */
    public function output_01_kaufman()
    {
        $board = new Board();

        $board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        (new BoardToPng($board))->output(self::OUTPUT_FOLDER . '/tmp.png');

        $this->assertEquals(
            md5(file_get_contents(self::OUTPUT_FOLDER . '/tmp.png')),
            md5(file_get_contents(self::DATA_FOLDER . '/img/01_kaufman.png'))
        );
    }
}
