<?php

namespace Chess\Tests\Unit\Function;

use Chess\Eval\FastFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class FastFunctionTest extends AbstractUnitTestCase
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
        ];

        $this->assertSame($expected, FastFunction::names());
    }

    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedNormd = [ -1.0, -0.3333, 0.0112, 0.0865, 0.0865, 0.0865, 0.4325, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.0462;
        $expectedMedian = 0.0865;
        $expectedMode = 0.0865;
        $expectedVar = 0.288;
        $expectedSd = 0.5367;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $normd = array_filter(FastFunction::normalization($board));
        sort($normd);

        $steinitz = FastFunction::steinitz($board);
        $mean = FastFunction::mean($board);
        $median = FastFunction::median($board);
        $mode = FastFunction::mode($board);
        $var = FastFunction::var($board);
        $sd = FastFunction::sd($board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }
}
