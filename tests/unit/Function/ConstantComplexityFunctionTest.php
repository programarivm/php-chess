<?php

namespace Chess\Tests\Unit;

use Chess\Function\ConstantComplexityFunction;
use Chess\Tests\AbstractUnitTestCase;

class ConstantComplexityFunctionTest extends AbstractUnitTestCase
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
            'Protection',
            'Attack',
            'Discovered check',
            'Doubled pawn',
            'Passed pawn',
            'Advanced pawn',
            'Far-advanced pawn',
            'Isolated pawn',
            'Backward pawn',
            'Defense',
            'Absolute pin',
            'Relative pin',
            'Absolute fork',
            'Relative fork',
            'Outpost square',
            'Knight outpost',
            'Bishop outpost',
            'Bishop pair',
            'Bad bishop',
            'Diagonal opposition',
            'Direct opposition',
        ];

        $this->assertSame($expected, (new ConstantComplexityFunction())->names());
    }
}
