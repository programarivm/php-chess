<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\Evaluation\DefenseEvaluation;
use Chess\Tests\AbstractUnitTestCase;

class DefenseEvaluationTest extends AbstractUnitTestCase
{
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

        $defenseEval = (new DefenseEvaluation($board))->eval();

        $expected = [
            'w' => [
                'a2', 'b1', 'b2', 'c1', 'c2', 'd1', 'd2', 'd2', 'd2', 'd2',
                'e1', 'e1', 'f2', 'h2',
            ],
            'b' => [
                'a6', 'a6', 'b7', 'c6', 'c6', 'c7', 'c8', 'c8', 'd7', 'd7',
                'd7', 'd8', 'd8', 'e8', 'f7', 'f8', 'g7', 'g8', 'h7',
            ],
        ];

        $this->assertSame($expected, $defenseEval);
    }
}
