<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Play\SanPlay;
use Chess\Tutor\PgnExplanation;
use Chess\Tests\AbstractUnitTestCase;

class PgnExplanationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A08()
    {
        $expected = [
            "Black has a kind of space advantage.",
            "The pawn on c5 is unprotected.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->getBoard();

        $paragraph = (new PgnExplanation('d4', $board->toFen()))
            ->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expected = [
            "White has a decisive material advantage.",
            "White is just controlling the center.",
            "White has a total space advantage.",
            "The white pieces are timidly approaching the other side's king.",
            "The bishop on e6 is unprotected.",
        ];

        $paragraph = (new PgnExplanation('Bxe6+', '8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}
