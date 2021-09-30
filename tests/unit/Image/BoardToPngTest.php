<?php

namespace Chess\Tests\Unit\Image;

use Chess\Board;
use Chess\FEN\StringToBoard;
use Chess\Image\BoardToPng;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\FianchettoVariation as BenoniFianchettoVariation;

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
        $board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        (new BoardToPng($board))->output(self::OUTPUT_FOLDER . '/tmp.png');

        $this->assertEquals(
            md5(file_get_contents(self::OUTPUT_FOLDER . '/tmp.png')),
            md5(file_get_contents(self::DATA_FOLDER . '/img/01_kaufman.png'))
        );
    }

    /**
     * @test
     */
    public function output_02_kaufman()
    {
        $board = (new StringToBoard('3r2k1/p2r1p1p/1p2p1p1/q4n2/3P4/PQ5P/1P1RNPP1/3R2K1 b - - bm Nxd4'))
            ->create();

        (new BoardToPng($board))->output(self::OUTPUT_FOLDER . '/tmp.png');

        $this->assertEquals(
            md5(file_get_contents(self::OUTPUT_FOLDER . '/tmp.png')),
            md5(file_get_contents(self::DATA_FOLDER . '/img/02_kaufman.png'))
        );
    }

    /**
     * @test
     */
    public function output_benoni_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        (new BoardToPng($board))->output(self::OUTPUT_FOLDER . '/tmp.png');

        $this->assertEquals(
            md5(file_get_contents(self::OUTPUT_FOLDER . '/tmp.png')),
            md5(file_get_contents(self::DATA_FOLDER . '/img/benoni_fianchetto_variation.png'))
        );
    }
}
