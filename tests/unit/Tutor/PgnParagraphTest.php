<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\Tutor\PgnParagraph;
use Chess\Tests\AbstractUnitTestCase;

class PgnParagraphTest extends AbstractUnitTestCase
{
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
            "Black's king on f7 is unprotected.",
            "The bishop on e6 is unprotected.",
        ];

        $paragraph = (new PgnParagraph('Bxe6+', '8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1'))
            ->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}
