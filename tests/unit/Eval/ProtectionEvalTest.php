<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\ProtectionEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class ProtectionEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $protectionEval = new ProtectionEval(new Board());

        $this->assertSame($expectedResult, $protectionEval->result);
    }

    /**
     * @test
     */
    public function e4_d5()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has better protected pieces.",
        ];

        $expectedElaboration = [
            "The pawn on e4 is unprotected.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'd5');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
        $this->assertSame($expectedExplanation, $protectionEval->explain());
        $this->assertSame($expectedElaboration, $protectionEval->elaborate());
    }

    /**
     * @test
     */
    public function B56()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/opening/B56.pgn');
        $board = (new SanPlay($B56))->validate()->board;
        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
    }

    /**
     * @test
     */
    public function B25()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/opening/B25.pgn');
        $board = (new SanPlay($B25))->validate()->board;
        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Nxe5()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 6.53,
        ];

        $expectedExplanation = [
            "Black has better protected pieces.",
        ];

        $expectedElaboration = [
            "The knight on e5 is unprotected.",
            "The bishop on b5 is unprotected.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'a6');
        $board->play('w', 'Nxe5');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
        $this->assertSame($expectedExplanation, $protectionEval->explain());
        $this->assertSame($expectedElaboration, $protectionEval->elaborate());
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_a3_Nxe4_d3()
    {
        $expectedResult = [
            'w' => 4.2,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has better protected pieces.",
        ];

        $expectedElaboration = [
            "The pawn on e5 is unprotected.",
            "The knight on e4 is unprotected.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'a3');
        $board->play('b', 'Nxe4');
        $board->play('w', 'd3');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
        $this->assertSame($expectedExplanation, $protectionEval->explain());
        $this->assertSame($expectedElaboration, $protectionEval->elaborate());
    }

    /**
     * @test
     */
    public function D07()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $B56 = file_get_contents(self::DATA_FOLDER.'/opening/D07.pgn');
        $board = (new SanPlay($B56))->validate()->board;
        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
    }

    /**
     * @test
     */
    public function c5_pawn()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('r2q1rk1/pb1nbppp/2p1pn2/1pPp4/3P4/1PN2NP1/P1Q1PPBP/R1BR2K1 b - -');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
        $this->assertSame($expectedExplanation, $protectionEval->explain());
        $this->assertSame($expectedElaboration, $protectionEval->elaborate());
    }

    /**
     * @test
     */
    public function b7_pawn()
    {
        $expectedResult = [
            'w' => 2,
            'b' => 1,
        ];

        $expectedExplanation = [
            "White has better protected pieces.",
        ];

        $expectedElaboration = [
            "The pawn on c6 is unprotected.",
            "The pawn on b7 is unprotected.",
        ];

        $board = FenToBoardFactory::create('r7/pp3p2/2P2nk1/P1Pp1p2/3Pp3/4NpPq/1R1Q1P2/6K1 w - -');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->result);
        $this->assertSame($expectedExplanation, $protectionEval->explain());
        $this->assertSame($expectedElaboration, $protectionEval->elaborate());
    }
}
