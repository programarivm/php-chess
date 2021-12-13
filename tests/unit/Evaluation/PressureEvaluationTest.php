<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\PressureEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class PressureEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $pressEvald = (new PressureEvaluation(new Board()))->evaluate();

        $expected = [
            'w' => [],
            'b' => [],
        ];

        $this->assertSame($expected, $pressEvald);
    }

    /**
     * @test
     */
    public function open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $pressEvald = (new PressureEvaluation($board))->evaluate();

        $expected = [
            'w' => [],
            'b' => ['e4'],
        ];

        $this->assertSame($expected, $pressEvald);
    }

    /**
     * @test
     */
    public function closed_sicilian()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $pressEvald = (new PressureEvaluation($board))->evaluate();

        $expected = [
            'w' => [],
            'b' => ['c3'],
        ];

        $this->assertSame($expected, $pressEvald);
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

        $pressEvald = (new PressureEvaluation($board))->evaluate();

        $expected = [
            'w' => ['a6', 'c6', 'c6', 'd7', 'f7'],
            'b' => ['b5', 'e5'],
        ];

        $this->assertSame($expected, $pressEvald);
    }
}
