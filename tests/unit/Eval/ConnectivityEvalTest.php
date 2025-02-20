<?php

namespace Chess\Tests\Unit\Eval\Material;

use Chess\Eval\ConnectivityEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class ConnectivityEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expectedResult = [
            'w' => 2,
            'b' => 2,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
            "These pieces are hanging: The rook on a1, the rook on h1, the rook on a8, the rook on h8.",
        ];

        $connectivityEval = new ConnectivityEval(new Board());

        $this->assertSame($expectedResult, $connectivityEval->result);
        $this->assertSame($expectedExplanation, $connectivityEval->explain());
        $this->assertSame($expectedElaboration, $connectivityEval->elaborate());
    }

    /**
     * @test
     */
    public function C60()
    {
        $expectedResult = [
            'w' => 5,
            'b' => 3,
        ];

        $expectedExplanation = [
            "The black pieces are significantly better connected.",
        ];

        $expectedElaboration = [
            "These pieces are hanging: The rook on a1, the rook on h1, the pawn on g2, the rook on a8, the rook on h8, the pawn on g7, the pawn on e4, the bishop on b5.",
        ];

        $C60 = file_get_contents(self::DATA_FOLDER.'/opening/C60.pgn');
        $board = (new SanPlay($C60))->validate()->board;
        $connectivityEval = new ConnectivityEval($board);

        $this->assertSame($expectedResult, $connectivityEval->result);
        $this->assertSame($expectedExplanation, $connectivityEval->explain());
        $this->assertSame($expectedElaboration, $connectivityEval->elaborate());
    }
}
