<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\Heuristics\SanHeuristics;
use Chess\ML\Supervised\Regression\GeometricSumLabeller;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class GeometricSumLabellerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A00_labelled()
    {
        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->getBoard();

        $balance = (new SanHeuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -16438.0;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $balance = (new SanHeuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -1032155.82;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A59_labelled()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->getBoard();

        $balance = (new SanHeuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -526213.02;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function B56_labelled()
    {
        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SanPlay($B56))->validate()->getBoard();

        $balance = (new SanHeuristics($board->getMovetext()))->getBalance();

        $end = end($balance);

        $label = (new GeometricSumLabeller())->label($end);

        $expected = -13.96;

        $this->assertSame($expected, $label);
    }
}
