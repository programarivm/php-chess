<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\Heuristics;
use Chess\Tests\AbstractUnitTestCase;

class HeuristicsTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_names()
    {
        $expected = [
            'Material',
            'Center',
            'Connectivity',
            'Space',
            'Pressure',
            'King safety',
            'Tactics',
            'Attack',
            'Doubled pawn',
            'Passed pawn',
            'Isolated pawn',
            'Backward pawn',
            'Absolute pin',
            'Relative pin',
            'Absolute fork',
            'Relative fork',
            'Square outpost',
            'Knight outpost',
            'Bishop outpost',
            'Bishop pair',
            'Bad bishop',
            'Direct opposition',
        ];

        $this->assertSame($expected, (new Heuristics())->names());
    }

    /**
     * @test
     */
    public function get_weights()
    {
        $expected = [
            16,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
        ];

        $this->assertSame($expected, (new Heuristics())->weights());
    }
}
