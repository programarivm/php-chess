<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\CheckmateInOneEvaluation;
use Chess\Tests\AbstractUnitTestCase;

class CheckmateInOneEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $checkmateEval = (new CheckmateInOneEvaluation($board))->eval();

        $this->assertSame($expected, $checkmateEval);
    }

    /**
     * @test
     */
    public function f3_e5_g4()
    {
        $board = new Board();
        $board->play('w', 'f3');
        $board->play('b', 'e5');
        $board->play('w', 'g4');

        $expected = [
            'w' => 1,
            'b' => 0,
        ];

        $checkmateEval = (new CheckmateInOneEvaluation($board))->eval();

        $this->assertSame($expected, $checkmateEval);
    }

    /**
     * @test
     */
    public function e4_e5_Qh5_Nc6_Bc4_Nf6()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Qh5');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Nf6');

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $checkmateEval = (new CheckmateInOneEvaluation($board))->eval();

        $this->assertSame($expected, $checkmateEval);
    }
}
