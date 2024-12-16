<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Glossary\ConnectedRooksTerm;
use Chess\Tests\AbstractUnitTestCase;

class ConnectedRooksTermTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_09()
    {
        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('r3k2r/pbn2ppp/8/1P1pP3/P1qP4/5B2/3Q1PPP/R3K2R w KQkq -');

        $connectedRooksTerm = new ConnectedRooksTerm($board);

        $this->assertSame($expectedElaboration, $connectedRooksTerm->elaborate());
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $expectedElaboration = [
            "White has connected rooks.",
            "Black has connected rooks.",
        ];

        $board = FenToBoardFactory::create('1r2r1k1/p4p1p/6pB/q7/8/3Q2P1/PbP2PKP/1R3R2 w - -');

        $connectedRooksTerm = new ConnectedRooksTerm($board);

        $this->assertSame($expectedElaboration, $connectedRooksTerm->elaborate());
    }
}
