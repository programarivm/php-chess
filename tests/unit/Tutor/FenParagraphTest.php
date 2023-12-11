<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Play\SanPlay;
use Chess\Tutor\FenParagraph;
use Chess\Tests\AbstractUnitTestCase;

class FenParagraphTest extends AbstractUnitTestCase
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

        $paragraph = (new FenParagraph($board->toFen()))->getParagraph();

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
            "The knight on e6 is pinned so it can't be moved because the king would be put in check.",
            "White has the bishop pair.",
        ];

        $paragraph = (new FenParagraph('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}
