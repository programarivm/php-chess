<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AttackEval;
use Chess\Play\SAN;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class AttackEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $attEval = (new AttackEval(new Board()))->eval();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEval);
    }

    /**
     * @test
     */
    public function B25()
    {
        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');

        $board = (new SAN($B25))->play()->getBoard();

        $attEval = (new AttackEval($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEval);
    }

    /**
     * @test
     */
    public function B56()
    {
        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SAN($B56))->play()->getBoard();

        $attEval = (new AttackEval($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEval);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Nxe5()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'a6');
        $board->play('w', 'Nxe5');

        $attEval = (new AttackEval($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 2.33,
        ];

        $this->assertSame($expected, $attEval);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_a3_Nxe4_d3()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'a3');
        $board->play('b', 'Nxe4');
        $board->play('w', 'd3');

        $attEval = (new AttackEval($board))->eval();

        $expected = [
            'w' => 2.2,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEval);
    }

    /**
     * @test
     */
    public function e4_Nf6_e5()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'Nf6');
        $board->play('w', 'e5');

        $attEval = (new AttackEval($board))->eval();

        $expected = [
            'w' => 2.2,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEval);
    }
}
