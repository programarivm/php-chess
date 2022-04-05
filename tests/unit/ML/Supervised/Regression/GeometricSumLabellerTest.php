<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\Board;
use Chess\Heuristics;
use Chess\ML\Supervised\Regression\GeometricSumLabeller;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\Sicilian\Open as ClosedSicilian;

class GeometricSumLabellerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start_labelled()
    {
        $board = new Board();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = 0.0;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function fool_checkmate_labelled()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -4148.4;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = 4132.64;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function benko_gambit_labelled()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -1544.35;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function closed_sicilian_labelled()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -3.22;

        $this->assertSame($expected, $label);
    }
}
