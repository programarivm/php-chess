<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\ProtectionEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

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

        $this->assertSame($expectedResult, $protectionEval->getResult());
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

        $expectedPhrase = [
            "Black has a tiny protection advantage.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'd5');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->getResult());
        $this->assertSame($expectedPhrase, $protectionEval->getPhrases());
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

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');
        $board = (new SanPlay($B56))->validate()->getBoard();
        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->getResult());
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

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->getBoard();
        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->getResult());
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

        $expectedPhrase = [
            "Black has a decisive protection advantage.",
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

        $this->assertSame($expectedResult, $protectionEval->getResult());
        $this->assertSame($expectedPhrase, $protectionEval->getPhrases());
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

        $expectedPhrase = [
            "White has a decisive protection advantage.",
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

        $this->assertSame($expectedResult, $protectionEval->getResult());
        $this->assertSame($expectedPhrase, $protectionEval->getPhrases());
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

        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/D07.pgn');
        $board = (new SanPlay($B56))->validate()->getBoard();
        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->getResult());
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

        $expectedPhrase = [];

        $board = FenToBoardFactory::create('r2q1rk1/pb1nbppp/2p1pn2/1pPp4/3P4/1PN2NP1/P1Q1PPBP/R1BR2K1 b - -');

        $protectionEval = new ProtectionEval($board);

        $this->assertSame($expectedResult, $protectionEval->getResult());
        $this->assertSame($expectedPhrase, $protectionEval->getPhrases());
    }
}
