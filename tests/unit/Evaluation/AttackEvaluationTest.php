<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\AttackEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class AttackEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $attEvald = (new AttackEvaluation(new Board()))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEvald);
    }

    /**
     * @test
     */
    public function open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $attEvald = (new AttackEvaluation($board))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEvald);
    }

    /**
     * @test
     */
    public function closed_sicilian()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $attEvald = (new AttackEvaluation($board))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEvald);
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

        $attEvald = (new AttackEvaluation($board))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 2.33,
        ];

        $this->assertSame($expected, $attEvald);
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

        $attEvald = (new AttackEvaluation($board))->evaluate();

        $expected = [
            'w' => 2.2,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEvald);
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

        $attEvald = (new AttackEvaluation($board))->evaluate();

        $expected = [
            'w' => 2.2,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEvald);
    }
}
