<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Array\AsciiArray;
use Chess\Evaluation\PassedPawnEvaluation;
use Chess\Tests\AbstractUnitTestCase;

class PassedPawnEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_13()
    {
        $position = [
            7 => [ ' . ', ' r ', ' . ', ' . ', ' . ', ' . ', ' k ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' n ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' B ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' P ' ],
            0 => [ ' . ', ' . ', ' . ', ' N ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $board = (new AsciiArray($position))->toBoard('w');

        $expected = [
            'w' => 0,
            'b' => 4,
        ];

        $passedPawnEval = (new PassedPawnEvaluation($board))->eval();

        $this->assertSame($expected, $passedPawnEval);
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $position = [
            7 => [ ' . ', ' r ', ' . ', ' . ', ' r ', ' . ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ', ' B ' ],
            4 => [ ' q ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' Q ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' P ', ' b ', ' P ', ' . ', ' . ', ' P ', ' K ', ' P ' ],
            0 => [ ' . ', ' R ', ' . ', ' . ', ' . ', ' R ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position))->toBoard('w');

        $expected = [
            'w' => 2,
            'b' => 0,
        ];

        $passedPawnEval = (new PassedPawnEvaluation($board))->eval();

        $this->assertSame($expected, $passedPawnEval);
    }

    /**
     * @test
     */
    public function kaufman_21()
    {
        $position = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            6 => [ ' . ', ' B ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' K ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' p ', ' . ', ' . ', ' b ', ' n ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' k ', ' . ', ' . ' ],
            0 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position))->toBoard('w');

        $expected = [
            'w' => 0,
            'b' => 11,
        ];

        $passedPawnEval = (new PassedPawnEvaluation($board))->eval();

        $this->assertSame($expected, $passedPawnEval);
    }
}
