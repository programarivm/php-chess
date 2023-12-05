<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Tutor\FenParagraph;
use Chess\Tests\AbstractUnitTestCase;

class FenParagraphTest extends AbstractUnitTestCase
{
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

        $sentence = (new FenParagraph('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

        $this->assertSame($expected, $sentence);
    }
}
