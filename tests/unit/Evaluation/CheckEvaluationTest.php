<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\CheckEvaluation;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\RuyLopez\LucenaDefense as RuyLopezLucenaDefense;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class CheckEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function ruy_lopez_lucena_defense()
    {
        $board = (new RuyLopezLucenaDefense(new Board()))->play();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $checkEvald = (new CheckEvaluation($board))->evaluate();

        $this->assertSame($expected, $checkEvald);
    }

    /**
     * @test
     */
    public function sicilian_open()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $checkEvald = (new CheckEvaluation($board))->evaluate();

        $this->assertSame($expected, $checkEvald);
    }

    /**
     * @test
     */
    public function sicilian_closed()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $checkEvald = (new CheckEvaluation($board))->evaluate();

        $this->assertSame($expected, $checkEvald);
    }

    /**
     * @test
     */
    public function fool_checkmate()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $checkEvald = (new CheckEvaluation($board))->evaluate();

        $this->assertSame($expected, $checkEvald);
    }

    /**
     * @test
     */
    public function scholar_checkmate()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $expected = [
            'w' => 1,
            'b' => 0,
        ];

        $checkEvald = (new CheckEvaluation($board))->evaluate();

        $this->assertSame($expected, $checkEvald);
    }
}
