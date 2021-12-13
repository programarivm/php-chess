<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\TacticsEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class TacticsEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $attEvald = (new TacticsEvaluation(new Board()))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $attEvald);
    }

    /**
     * @test
     */
    public function e4_d5()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'd5');

        $tacticsEvald = (new TacticsEvaluation($board))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $this->assertSame($expected, $tacticsEvald);
    }

    /**
     * @test
     */
    public function open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $attEvald = (new TacticsEvaluation($board))->evaluate();

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

        $attEvald = (new TacticsEvaluation($board))->evaluate();

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

        $tacticsEvald = (new TacticsEvaluation($board))->evaluate();

        $expected = [
            'w' => 0,
            'b' => 6.53,
        ];

        $this->assertSame($expected, $tacticsEvald);
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

        $tacticsEvald = (new TacticsEvaluation($board))->evaluate();

        $expected = [
            'w' => 4.2,
            'b' => 0,
        ];

        $this->assertSame($expected, $tacticsEvald);
    }
}
