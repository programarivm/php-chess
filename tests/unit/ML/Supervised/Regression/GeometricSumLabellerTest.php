<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\SanHeuristic;
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
        $name = 'Material';

        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->getBoard();

        $balance = (new SanHeuristic($name, $board->getMovetext()))->getBalance();

        $label = (new GeometricSumLabeller())->label($balance);

        $expected = 0.0;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $name = 'Center';

        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $balance = (new SanHeuristic($name, $board->getMovetext()))->getBalance();

        $label = (new GeometricSumLabeller())->label($balance);

        $expected = 17.2;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A59_labelled()
    {
        $name = 'Connectivity';

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->getBoard();

        $balance = (new SanHeuristic($name, $board->getMovetext()))->getBalance();

        $label = (new GeometricSumLabeller())->label($balance);

        $expected = -52753.72;

        $this->assertSame($expected, $label);
    }
}
