<?php

namespace Chess\Tests\Unit\Media;

use Chess\Board;
use Chess\FEN\StrToBoard;
use Chess\Media\BoardToPng;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\QueensGambit\SymmetricalDefense as QueensGambitSymmetricalDefense;

class BoardToPngTest extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../output';

    public static function tearDownAfterClass(): void
    {
        array_map('unlink', glob(self::OUTPUT_FOLDER . '/*.png'));
    }

    /**
     * @test
     */
    public function output_00_starting()
    {
        $board = new Board();

        $filename = (new BoardToPng($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/00_starting.png')
        );
    }

    /**
     * @test
     */
    public function output_01_kaufman_flip()
    {
        $board = (new StrToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $filename = (new BoardToPng($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/01_kaufman_flip.png')
        );
    }

    /**
     * @test
     */
    public function output_01_kaufman()
    {
        $board = (new StrToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $filename = (new BoardToPng($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/01_kaufman.png')
        );
    }

    /**
     * @test
     */
    public function output_02_kaufman()
    {
        $board = (new StrToBoard('3r2k1/p2r1p1p/1p2p1p1/q4n2/3P4/PQ5P/1P1RNPP1/3R2K1 b - - bm Nxd4'))
            ->create();

        $filename = (new BoardToPng($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/02_kaufman.png')
        );
    }

    /**
     * @test
     */
    public function output_benko_gambit_flip()
    {
        $board = (new BenkoGambit())->play();

        $filename = (new BoardToPng($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/benko_gambit_flip.png')
        );
    }

    /**
     * @test
     */
    public function output_benko_gambit()
    {
        $board = (new BenkoGambit())->play();

        $filename = (new BoardToPng($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/benko_gambit.png')
        );
    }

    /**
     * @test
     */
    public function output_symmetrical_defense_to_the_queens_gambit_flip()
    {
        $board = (new QueensGambitSymmetricalDefense())->play();

        $filename = (new BoardToPng($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/symmetrical_defense_to_the_queens_gambit_flip.png')
        );
    }

    /**
     * @test
     */
    public function output_symmetrical_defense_to_the_queens_gambit()
    {
        $board = (new QueensGambitSymmetricalDefense())->play();

        $filename = (new BoardToPng($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/img/symmetrical_defense_to_the_queens_gambit.png')
        );
    }
}
