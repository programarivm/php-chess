<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AttackEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class AttackEvalTest extends AbstractUnitTestCase
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

        $board = new Board();
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
    }

    /**
     * @test
     */
    public function B21()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 2.33,
        ];

        $expectedExplanation = [
            "Black has a slight attack advantage.",
        ];

        $expectedElaboration = [
            "The bishop on c4 is under threat of being attacked.",
        ];

        $board = FenToBoardFactory::create('r1bqkbnr/5ppp/p1npp3/1p6/2B1P3/2N2N2/PP2QPPP/R1B2RK1 w kq b6');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function B21_Bb3()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('r1bqkbnr/5ppp/p1npp3/1p6/4P3/1BN2N2/PP2QPPP/R1B2RK1 b kq -');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
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
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
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
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
    }

    /**
     * @test
     */
    public function middlegame()
    {
        $expectedResult = [
            'w' => 0.8700000000000001,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a slight attack advantage.",
        ];

        $expectedElaboration = [
            "The knight on b5 is under threat of being attacked.",
        ];

        $board = FenToBoardFactory::create('r1bqkbnr/5ppp/p1npp3/1n6/2B1P3/2N2N2/PP2QPPP/R1B2RK1 w kq b6');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1.0,
        ];

        $expectedExplanation = [
            "Black has a slight attack advantage.",
        ];

        $expectedElaboration = [
            "The pawn on d4 is under threat of being attacked.",
        ];

        $board = FenToBoardFactory::create('6k1/6p1/2n2b2/8/3P4/5N2/2K5/8 w - -');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function w_N_c2()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedExplanation = [
            "Black has a moderate attack advantage.",
        ];

        $board = FenToBoardFactory::create('2r3k1/8/8/2q5/8/8/2N5/1K6 w - -');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
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
            "Black has a total attack advantage.",
        ];

        $expectedElaboration = [
            "The bishop on b5 is under threat of being attacked.",
            "The knight on e5 is under threat of being attacked.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'a6');
        $board->play('w', 'Nxe5');

        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
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
            "White has a moderate attack advantage.",
        ];

        $expectedElaboration = [
            "The pawn on e5 is under threat of being attacked.",
            "The knight on e4 is under threat of being attacked.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'a3');
        $board->play('b', 'Nxe4');
        $board->play('w', 'd3');

        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function e4_Nf6_e5()
    {
        $expectedResult = [
            'w' => 2.2,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a slight attack advantage.",
        ];

        $expectedElaboration = [
            "The knight on f6 is under threat of being attacked.",
        ];

        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'Nf6');
        $board->play('w', 'e5');

        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a slight attack advantage.",
        ];

        $expectedElaboration = [
            "The pawn on e5 is under threat of being attacked.",
        ];

        $board = FenToBoardFactory::create('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function g3_in_check()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('8/p4pk1/6b1/3P1PQ1/8/P1q3K1/2p3B1/8 w - -');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }

    /**
     * @test
     */
    public function g1_in_check()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('b3k2r/5p2/pR2p3/r7/2pbPp2/P1N2R1P/1PP3P1/6K1 w k -');
        $attackEval = new AttackEval($board);

        $this->assertSame($expectedResult, $attackEval->result);
        $this->assertSame($expectedExplanation, $attackEval->explain());
        $this->assertSame($expectedElaboration, $attackEval->elaborate());
    }
}
