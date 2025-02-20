<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Eval\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\FenEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\FenToBoardFactory;

class FenEvaluationTest extends AbstractUnitTestCase
{
    static private CompleteFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new CompleteFunction();
    }

    /**
     * @test
     */
    public function A08()
    {
        $expected = [
            "Black has a slightly better control of the center.",
            "The white pieces are slightly better connected.",
            "Black has a moderate space advantage.",
            "Black's king has more safe squares to move to than its counterpart.",
            "These pieces are hanging: The rook on a1, the rook on h1, the rook on a8, the rook on h8, the pawn on c5.",
            "Overall, 2 evaluation features are favoring Black.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/opening/A08.pgn');
        $board = (new SanPlay($A08))->validate()->board;

        $paragraph = (new FenEvaluation(self::$f, $board))->paragraph;

        $this->assertEquals($expected, $paragraph);
    }

        /**
     * @test
     */
    public function futurize_A08()
    {
        $expected = [
            "Black will have a slightly better control of the center.",
            "The white pieces will be slightly better connected.",
            "Black will have a moderate space advantage.",
            "Black's king will have more safe squares to move to than its counterpart.",
            "These pieces will be hanging: The rook on a1, the rook on h1, the rook on a8, the rook on h8, the pawn on c5.",
            "Overall, 2 evaluation features will be favoring Black.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/opening/A08.pgn');
        $board = (new SanPlay($A08))->validate()->board;

        $paragraph = (new FenEvaluation(self::$f, $board))->futurize();

        $this->assertEquals($expected, $paragraph);
    }

    /**
     * @test
     */
    public function capablanca_f4()
    {
        $expected = [
            "White is totally controlling the center.",
            "The black pieces are slightly better connected.",
            "White has a total space advantage.",
            "The white player is pressuring more squares than its opponent.",
            "White's king has more safe squares to move to than its counterpart.",
            "These pieces are hanging: The rook on a8, Black's archbishop on c8, Black's chancellor on h8, the rook on j8, the pawn on i7, the pawn on f4, the pawn on i2, the rook on a1, White's archbishop on c1, White's chancellor on h1, the rook on j1.",
            "Overall, 3 evaluation features are favoring White.",
        ];

        $board = FenToBoardFactory::create('rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3');

        $paragraph = (new FenEvaluation(self::$f, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function futurize_capablanca_f4()
    {
        $expected = [
            "White will be totally controlling the center.",
            "The black pieces will be slightly better connected.",
            "White will have a total space advantage.",
            "The white player will be pressuring more squares than its opponent.",
            "White's king will have more safe squares to move to than its counterpart.",
            "These pieces will be hanging: The rook on a8, Black's archbishop on c8, Black's chancellor on h8, the rook on j8, the pawn on i7, the pawn on f4, the pawn on i2, the rook on a1, White's archbishop on c1, White's chancellor on h1, the rook on j1.",
            "Overall, 3 evaluation features will be favoring White.",
        ];

        $board = FenToBoardFactory::create('rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3');

        $paragraph = (new FenEvaluation(self::$f, $board))->futurize();

        $this->assertSame($expected, $paragraph);
    }
}
