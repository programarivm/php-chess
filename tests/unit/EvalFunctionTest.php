<?php

namespace Chess\Tests\Unit;

use Chess\EvalFunction;
use Chess\Tests\AbstractUnitTestCase;

class EvalFunctionTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function names()
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

        $this->assertSame($expected, (new EvalFunction())->names());
    }
}
