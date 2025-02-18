<?php

namespace Chess\Tests\Unit\Glossary;

use Chess\Glossary\ConnectedRooksTerm;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class ConnectedRooksTermTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedElaboration = [
            "Black has connected rooks.",
        ];

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $connectedRooksTerm = new ConnectedRooksTerm($board);

        $this->assertSame($expectedElaboration, $connectedRooksTerm->elaborate());
    }

    /**
     * @test
     */
    public function kaufman_07()
    {
        $expectedElaboration = [
            "White has connected rooks.",
            "Black has connected rooks.",
        ];

        $board = FenToBoardFactory::create('2r2rk1/1bqnbpp1/1p1ppn1p/pP6/N1P1P3/P2B1N1P/1B2QPP1/R2R2K1 b - -');

        $connectedRooksTerm = new ConnectedRooksTerm($board);

        $this->assertSame($expectedElaboration, $connectedRooksTerm->elaborate());
    }

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

    /**
     * @test
     */
    public function kaufman_15()
    {
        $expectedElaboration = [
            "White has connected rooks.",
        ];

        $board = FenToBoardFactory::create('r2q1r1k/pb3p1p/2n1p2Q/5p2/8/3B2N1/PP3PPP/R3R1K1 w - -');

        $connectedRooksTerm = new ConnectedRooksTerm($board);

        $this->assertSame($expectedElaboration, $connectedRooksTerm->elaborate());
    }
}
