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
}
