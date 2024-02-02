<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DiscoveredCheckEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class DiscoveredCheckEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b20_sicilian_defense()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedPhrase = [
            "The White king can be put in check as long as the pawn on e5 moves out of the way.",
        ];

        $board = (new StrToBoard('r1b1kbnr/pp3ppp/2n1q3/4p3/1pP5/P4N2/1B1P1PPP/RN1QKB1R w KQkq -'))
            ->create();

        $discoveredCheckEval = new DiscoveredCheckEval($board);

        $this->assertSame($expectedResult, $discoveredCheckEval->getResult());
        $this->assertSame($expectedPhrase, $discoveredCheckEval->getPhrases());
    }

    /**
     * @test
     */
    public function knight()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedPhrase = [
            "The White king can be put in check as long as the knight on c7 moves out of the way.",
        ];

        $board = (new StrToBoard('2r5/2n5/5k2/8/8/2K5/8/8 w - - 0 1'))
            ->create();

        $discoveredCheckEval = new DiscoveredCheckEval($board);

        $this->assertSame($expectedResult, $discoveredCheckEval->getResult());
        $this->assertSame($expectedPhrase, $discoveredCheckEval->getPhrases());
    }

    /**
     * @test
     */
    public function knight_and_rook()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 8.3,
        ];

        $expectedPhrase = [
            "The White king can be put in check as long as the knight on c7 moves out of the way.",
            "The White king can be put in check as long as the rook on f6 moves out of the way.",
        ];

        $board = (new StrToBoard('2r4k/2n3b1/5r2/8/8/2K5/8/8 w - - 0 1'))
            ->create();

        $discoveredCheckEval = new DiscoveredCheckEval($board);

        $this->assertSame($expectedResult, $discoveredCheckEval->getResult());
        $this->assertSame($expectedPhrase, $discoveredCheckEval->getPhrases());
    }
}
