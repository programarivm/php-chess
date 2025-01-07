<?php

namespace Chess\Tests\Unit;

use Chess\Equilibrium;
use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class EquilibriumTest extends AbstractUnitTestCase
{
    /**
     * @test
     * @requires PHP 8.4
     */
    public function start()
    {
        $expectedPhi = 28.255;

        $board = new Board();

        $phi = new Equilibrium($board);

        $this->assertEqualsWithDelta($expectedPhi, $phi->result, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function kaufman_06()
    {
        $expectedPhi = 13.2852;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $phi = new Equilibrium($board);

        $this->assertEqualsWithDelta($expectedPhi, $phi->result, 0.0001);
    }
}
