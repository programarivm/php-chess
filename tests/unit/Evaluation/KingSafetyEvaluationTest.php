<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\KingSafetyEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class KingSafetyEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $kSafetyEvald = (new KingSafetyEvaluation(new Board()))->evaluate();

        $expected = [
            'w' => 1,
            'b' => 1,
        ];

        $this->assertSame($expected, $kSafetyEvald);
    }

    /**
     * @test
     */
    public function open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $kSafetyEvald = (new KingSafetyEvaluation($board))->evaluate();

        $expected = [
            'w' => 1,
            'b' => 1,
        ];

        $this->assertSame($expected, $kSafetyEvald);
    }

    /**
     * @test
     */
    public function closed_sicilian()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $kSafetyEvald = (new KingSafetyEvaluation($board))->evaluate();

        $expected = [
            'w' => 1,
            'b' => 1,
        ];

        $this->assertSame($expected, $kSafetyEvald);
    }
}
