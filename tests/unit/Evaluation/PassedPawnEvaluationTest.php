<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Ascii;
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

        $board = (new Ascii())->toBoard($position, 'w');

        $expected = [
            'w' => 0,
            'b' => 4,
        ];

        $passedPawnEvald = (new PassedPawnEvaluation($board))->evaluate();

        $this->assertSame($expected, $passedPawnEvald);
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

        $board = (new Ascii())->toBoard($position, 'w');

        $expected = [
            'w' => 2,
            'b' => 0,
        ];

        $passedPawnEvald = (new PassedPawnEvaluation($board))->evaluate();

        $this->assertSame($expected, $passedPawnEvald);
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

        $board = (new Ascii())->toBoard($position, 'w');

        $expected = [
            'w' => 0,
            'b' => 11,
        ];

        $passedPawnEvald = (new PassedPawnEvaluation($board))->evaluate();

        $this->assertSame($expected, $passedPawnEvald);
    }
}
