<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Play\SanPlay;
use Chess\Tutor\FenExplanation;
use Chess\Tests\AbstractUnitTestCase;

class FenExplanationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A08()
    {
        $expected = [
            "Black has a significant control of the center.",
            "The white pieces are so better connected.",
            "Black has a significant space advantage.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->getBoard();

        $paragraph = (new FenExplanation($board->toFen()))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expected = [
            "White has a significant material advantage.",
            "White has a significant control of the center.",
            "The white pieces are somewhat better connected.",
            "The white player is pressuring a little bit more squares than its opponent.",
            "The knight on e6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
            "White has the bishop pair.",
        ];

        $paragraph = (new FenExplanation('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}
