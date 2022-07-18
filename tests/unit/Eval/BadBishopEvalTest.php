<?php

declare(strict_types=1);

namespace Chess\Tests\Unit\Eval;

use Chess\Array\AsciiArray;
use Chess\Eval\BadBishopEval;
use Chess\Tests\AbstractUnitTestCase;

class BadBishopEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function black_bad_bishop()
    {
        $position = [
            7 => [ ' r ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            6 => [ ' . ', ' b ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' p ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            0 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position))->toBoard('w');

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $doubledPawnEval = (new BadBishopEval($board))->eval();

        $this->assertSame($expected, $doubledPawnEval);
    }
}
