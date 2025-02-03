<?php

namespace Chess\Tests\Unit\Media;

use Chess\FenToBoardFactory;
use Chess\Media\BoardToMp4;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Classical\Board as ClassicalBoard;

class BoardToMp4Test extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../tmp';

    public static function tearDownAfterClass(): void
    {
        array_map('unlink', glob(self::OUTPUT_FOLDER . '/*.mp4'));
    }

    /**
     * @test
     */
    public function output_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = new ClassicalBoard();

        $filename = (new BoardToMp4(
            $A74,
            $board,
            $flip = false
        ))->output(self::OUTPUT_FOLDER, 'A74');

        $this->assertTrue(file_exists(self::OUTPUT_FOLDER.'/'.$filename));
    }

    /**
     * @test
     */
    public function output_960_QRKRNNBB()
    {
        $fen = 'qrkr1nbb/pppp2pp/3n1p2/4p3/4P3/4NP2/PPPP2PP/QRKRN1BB w KQkq -';

        $startPos = ['Q', 'R', 'K', 'R', 'N', 'N', 'B', 'B'];

        $board = FenToBoardFactory::create($fen, new Chess960Board($startPos));

        $movetext = '1.Bf2 Re8 2.Nd3 O-O-O 3.O-O';

        $filename = (new BoardToMp4(
            $movetext,
            $board,
            $flip = false
        ))->output(self::OUTPUT_FOLDER);

        $this->assertTrue(file_exists(self::OUTPUT_FOLDER.'/'.$filename));
    }

    /**
     * @test
     */
    public function output_960_BNNBQRKR()
    {
        $fen = 'b4rkr/ppppqppp/2nnpb2/8/4P3/2PP4/PP1NNPPP/B2BQRKR w KQkq -';

        $startPos = ['B', 'N', 'N', 'B', 'Q', 'R', 'K', 'R'];

        $board = FenToBoardFactory::create($fen, new Chess960Board($startPos));

        $movetext = '1.Bc2 O-O-O 2.Qc1 Rhe8 3.Rd1 h6 4.O-O';

        $filename = (new BoardToMp4(
            $movetext,
            $board,
            $flip = false
        ))->output(self::OUTPUT_FOLDER);

        $this->assertTrue(file_exists(self::OUTPUT_FOLDER.'/'.$filename));
    }
}
