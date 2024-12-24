<?php

namespace Chess\Tests\Unit;

use Chess\EvalGuesser;
use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;

class EvalGuesserTest extends AbstractUnitTestCase
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
        $expectedGuess = -1.16;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $balance = EvalGuesser::balance(self::$function, $board);

        $this->assertSame($expectedGuess, array_sum($balance));
    }
}
