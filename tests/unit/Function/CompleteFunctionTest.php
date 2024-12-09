<?php

namespace Chess\Tests\Unit\Function;

use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;

class CompleteFunctionTest extends AbstractUnitTestCase
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
            'Discovered check',
            'Doubled pawn',
            'Passed pawn',
            'Advanced pawn',
            'Far-advanced pawn',
            'Isolated pawn',
            'Backward pawn',
            'Defense',
            'Absolute skewer',
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
            'Overloading',
            'Back-rank threat',
            'Flight square',
            'Attack',
            'Checkability',
        ];

        $this->assertSame($expected, (new CompleteFunction())->names());
    }
}
