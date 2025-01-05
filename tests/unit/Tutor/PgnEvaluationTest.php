<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\PgnEvaluation;
use Chess\Tests\AbstractUnitTestCase;

class PgnEvaluationTest extends AbstractUnitTestCase
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
            "Black has a slight space advantage.",
            "White has a slight protection advantage.",
            "White has a slight attack advantage.",
            "White's king can be checked so it is vulnerable to forced moves.",
            "These pieces are hanging: The rook on a1, the rook on h1, the rook on a8, the rook on h8, the pawn on c5.",
            "The pawn on c5 is unprotected.",
            "The pawn on c5 is under threat of being attacked.",
            "Overall, 0 evaluation features are favoring either player.",
            "The mean evaluation of this position is 0.2235.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->board;

        $paragraph = (new PgnEvaluation('d4', self::$f, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expected = [
            "White is totally controlling the center.",
            "The black pieces are slightly better connected.",
            "White has a total space advantage.",
            "The white pieces are timidly approaching the other side's king.",
            "Black has a decisive protection advantage.",
            "These pieces are hanging: The bishop on e6.",
            "The bishop on e6 is unprotected.",
            "Overall, 4 evaluation features are favoring White.",
            "The mean evaluation of this position is 0.05702.",
        ];

        $board = FenToBoardFactory::create('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1');

        $paragraph = (new PgnEvaluation('Bxe6+', self::$f, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }
}
