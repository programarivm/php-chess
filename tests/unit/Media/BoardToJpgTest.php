<?php

namespace Chess\Tests\Unit\Media;

use Chess\Media\BoardToJpg;
use Chess\Play\SAN;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\Board as ClassicalBoard;

class BoardToJpgTest extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../tmp';

    public static function tearDownAfterClass(): void
    {
        array_map('unlink', glob(self::OUTPUT_FOLDER . '/*.jpg'));
    }

    /**
     * @test
     */
    public function output_start()
    {
        $board = new ClassicalBoard();

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/start.jpg')
        );
    }

    /**
     * @test
     */
    public function output_01_kaufman()
    {
        $fen = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+';

        $board = (new ClassicalFenStrToBoard($fen))->create();

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/01_kaufman.jpg')
        );
    }

    /**
     * @test
     */
    public function output_01_kaufman_flip()
    {
        $fen = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+';

        $board = (new ClassicalFenStrToBoard($fen))->create();

        $filename = (new BoardToJpg($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/01_kaufman_flip.jpg')
        );
    }

    /**
     * @test
     */
    public function output_02_kaufman()
    {
        $fen = '3r2k1/p2r1p1p/1p2p1p1/q4n2/3P4/PQ5P/1P1RNPP1/3R2K1 b - - bm Nxd4';

        $board = (new ClassicalFenStrToBoard($fen))->create();

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/02_kaufman.jpg')
        );
    }

    /**
     * @test
     */
    public function output_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SAN($A59))->play()->getBoard();

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/A59.jpg')
        );
    }

    /**
     * @test
     */
    public function output_A59_flip()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SAN($A59))->play()->getBoard();

        $filename = (new BoardToJpg($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/A59_flip.jpg')
        );
    }

    /**
     * @test
     */
    public function output_D06()
    {
        $D06 = file_get_contents(self::DATA_FOLDER.'/sample/D06.pgn');

        $board = (new SAN($D06))->play()->getBoard();

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/symmetrical_defense_to_the_queens_gambit.jpg')
        );
    }

    /**
     * @test
     */
    public function output_D06_flip()
    {
        $D06 = file_get_contents(self::DATA_FOLDER.'/sample/D06.pgn');

        $board = (new SAN($D06))->play()->getBoard();

        $filename = (new BoardToJpg($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/symmetrical_defense_to_the_queens_gambit_flip.jpg')
        );
    }

    /**
     * @test
     */
    public function output_start_capablanca()
    {
        $board = new CapablancaBoard();

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/start_capablanca.jpg')
        );
    }

    /**
     * @test
     */
    public function output_capablanca_Nj3_e5___Ci6_O_O()
    {
        $board = new CapablancaBoard();

        $board->play('w', 'Nj3');
        $board->play('b', 'e5');
        $board->play('w', 'Ci3');
        $board->play('b', 'Nc6');
        $board->play('w', 'h3');
        $board->play('b', 'b6');
        $board->play('w', 'Bh2');
        $board->play('b', 'Ci6');
        $board->play('w', 'O-O');

        $filename = (new BoardToJpg($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            sha1_file(self::OUTPUT_FOLDER.'/'.$filename),
            sha1_file(self::DATA_FOLDER.'/img/Nj3_e5___capablanca.jpg')
        );
    }
}
