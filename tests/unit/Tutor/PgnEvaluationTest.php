<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\PgnEvaluation;
use Chess\Tests\AbstractUnitTestCase;

class PgnEvaluationTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
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
            "Black has a checkability advantage.",
            "The pawn on c5 is unprotected.",
            "The c5-square is under threat of being attacked.",
            "White's king on e1 can be checked so it is vulnerable to forcing moves.",
            "Overall, 3 heuristic evaluation features are favoring White while 3 are favoring Black.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->board;

        $paragraph = (new PgnEvaluation('d4', self::$function, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expected = [
            "White is totally controlling the center.",
            "White has a total space advantage.",
            "The white pieces are timidly approaching the other side's king.",
            "Black has a decisive protection advantage.",
            "The bishop on e6 is unprotected.",
            "Overall, 7 heuristic evaluation features are favoring White while 1 is favoring Black.",
        ];

        $board = FenToBoardFactory::create('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1');

        $paragraph = (new PgnEvaluation('Bxe6+', self::$function, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }
}
