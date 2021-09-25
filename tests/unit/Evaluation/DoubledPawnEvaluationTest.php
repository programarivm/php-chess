<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Ascii;
use Chess\Evaluation\DoubledPawnEvaluation;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class DoubledPawnEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_17()
    {
        $position = [
            7 => [ ' . ', ' r ', ' . ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' p ', ' . ', ' . ', ' p ', ' b ', ' p ' ],
            5 => [ ' . ', ' . ', ' p ', ' p ', ' . ', ' k ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' B ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' Q ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' . ', ' R ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $board = (new Ascii())->toBoard($position, Symbol::WHITE);

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 1,
        ];

        $doubledPawnEvald = (new DoubledPawnEvaluation($board))->evaluate();

        $this->assertEquals($expected, $doubledPawnEvald);
    }
}
