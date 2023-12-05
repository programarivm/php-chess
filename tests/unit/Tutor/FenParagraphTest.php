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
            "Black has a remarkable control of the center.",
            "The white pieces are absolutely better connected.",
            "Black has a somewhat better space advantage.",
        ];

        $sentence = (new FenParagraph('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

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
            "White has a remarkable control of the center.",
            "The white pieces are slightly better connected.",
            "Black's knight on e6 is pinned.",
            "White has the bishop pair.",
        ];

        $paragraph = (new FenParagraph('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}
