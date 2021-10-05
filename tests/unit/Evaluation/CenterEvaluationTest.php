<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\CenterEvaluation;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\RuyLopez\LucenaDefense as RuyLopezLucenaDefense;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class CenterEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function ruy_lopez_lucena_defense()
    {
        $board = (new RuyLopezLucenaDefense(new Board()))->play();

        $expected = [
            Symbol::WHITE => 3,
            Symbol::BLACK => 2,
        ];

        $ctrEvald = (new CenterEvaluation($board))->evaluate();

        $this->assertEquals($expected, $ctrEvald);
    }

    /**
     * @test
     */
    public function sicilian_open()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $expected = [
            Symbol::WHITE => 5.2,
            Symbol::BLACK => 2,
        ];

        $ctrEvald = (new CenterEvaluation($board))->evaluate();

        $this->assertEquals($expected, $ctrEvald);
    }

    /**
     * @test
     */
    public function sicilian_closed()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $expected = [
            Symbol::WHITE => 2,
            Symbol::BLACK => 2,
        ];

        $ctrEvald = (new CenterEvaluation($board))->evaluate();

        $this->assertEquals($expected, $ctrEvald);
    }
}
