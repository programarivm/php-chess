<?php

namespace Chess\Tests\Unit;

use Chess\EvalArray;
use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;

class EvalArrayTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedGuess = 0.42;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $sum = EvalArray::sum(self::$function, $board);

        $this->assertSame($expectedGuess, $sum);
    }
}
