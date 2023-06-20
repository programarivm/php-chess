<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\Heuristics;
use Chess\ML\Supervised\Regression\GeometricSumLabeller;
use Chess\Play\SAN;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

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
    public function A00_labelled()
    {
        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SAN($A00))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -50.9;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SAN($movetext))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -1048541.64;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A59_labelled()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SAN($A59))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -210620.85;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function B56_labelled()
    {
        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SAN($B56))->play()->getBoard();

        $balance = (new Heuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -7.3;

        $this->assertSame($expected, $label);
    }
}
