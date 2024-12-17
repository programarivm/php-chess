<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Glossary\OpenFileTerm;
use Chess\Tests\AbstractUnitTestCase;

class OpenFileTermTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedElaboration = [
            "These are open files: c.",
        ];

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $openFileTerm = new OpenFileTerm($board);

        $this->assertSame($expectedElaboration, $openFileTerm->elaborate());
    }

    /**
     * @test
     */
    public function kaufman_07()
    {
        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('2r2rk1/1bqnbpp1/1p1ppn1p/pP6/N1P1P3/P2B1N1P/1B2QPP1/R2R2K1 b - -');

        $openFileTerm = new OpenFileTerm($board);

        $this->assertSame($expectedElaboration, $openFileTerm->elaborate());
    }

    /**
     * @test
     */
    public function kaufman_09()
    {
        $expectedElaboration = [
            "These are open files: c.",
        ];

        $board = FenToBoardFactory::create('r3k2r/pbn2ppp/8/1P1pP3/P1qP4/5B2/3Q1PPP/R3K2R w KQkq -');

        $openFileTerm = new OpenFileTerm($board);

        $this->assertSame($expectedElaboration, $openFileTerm->elaborate());
    }
}
