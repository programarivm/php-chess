<?php

namespace Chess\Tests\Unit\Media;

use Chess\Media\BoardToMp4;
use Chess\Tests\AbstractUnitTestCase;
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

        $filename = (new BoardToMp4(
            ClassicalBoard::VARIANT,
            $A74
        ))->output(self::OUTPUT_FOLDER, 'A74');

        $this->assertTrue(file_exists(self::OUTPUT_FOLDER.'/'.$filename));
    }

    /**
     * @test
     */
    public function output_960_QRKRNNBB()
    {
        $movetext = '1.Bf2 Re8 2.Nd3 O-O-O 3.O-O';

        $fen = 'qrkr1nbb/pppp2pp/3n1p2/4p3/4P3/4NP2/PPPP2PP/QRKRN1BB w KQkq -';

        $startPos = 'QRKRNNBB';

        $filename = (new BoardToMp4(
            ClassicalBoard::VARIANT,
            $movetext,
            $fen,
            $startPos
        ))->output(self::OUTPUT_FOLDER);

        $this->assertTrue(file_exists(self::OUTPUT_FOLDER.'/'.$filename));
    }

    /**
     * @test
     */
    public function output_960_BNNBQRKR()
    {
        $movetext = '1.Bc2 O-O-O 2.Qc1 Rhe8 3.Rd1 h6 4.O-O';

        $fen = 'b4rkr/ppppqppp/2nnpb2/8/4P3/2PP4/PP1NNPPP/B2BQRKR w KQkq -';

        $startPos = 'BNNBQRKR';

        $filename = (new BoardToMp4(
            ClassicalBoard::VARIANT,
            $movetext,
            $fen,
            $startPos
        ))->output(self::OUTPUT_FOLDER);

        $this->assertTrue(file_exists(self::OUTPUT_FOLDER.'/'.$filename));
    }
}
