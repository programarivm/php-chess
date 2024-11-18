<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\SanHeuristics;
use Chess\Function\CompleteFunction;
use Chess\ML\Supervised\Regression\GeometricSumLabeller;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class GeometricSumLabellerTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function A00_labelled()
    {
        $name = 'Material';

        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->board;

        $balance = (new SanHeuristics(self::$function, $board->movetext(), $name))->getBalance();

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

        $board = (new SanPlay($movetext))->validate()->board;

        $balance = (new SanHeuristics(self::$function, $board->movetext(), $name))->getBalance();

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

        $board = (new SanPlay($A59))->validate()->board;

        $balance = (new SanHeuristics(self::$function, $board->movetext(), $name))->getBalance();

        $label = (new GeometricSumLabeller())->label($balance);

        $expected = 3128.0;

        $this->assertSame($expected, $label);
    }
}
