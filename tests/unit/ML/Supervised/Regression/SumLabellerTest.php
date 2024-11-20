<?php

namespace Chess\Tests\Unit\ML\Supervised\Regression;

use Chess\SanHeuristics;
use Chess\Function\CompleteFunction;
use Chess\ML\Supervised\Regression\SumLabeller;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;

class SumLabellerTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function scholar_checkmate()
    {
        $name = 'Center';

        $movetext = file_get_contents(self::DATA_FOLDER.'/sample/scholar_checkmate.pgn');

        $board = (new SanPlay($movetext))->validate()->board;

        $balance = (new SanHeuristics(self::$function, $board->movetext(), $name))->getBalance();

        $label = (new SumLabeller())->label($balance);

        $expected = 1.58;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A00()
    {
        $name = 'Material';

        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->board;

        $balance = (new SanHeuristics(self::$function, $board->movetext(), $name))->getBalance();

        $label = (new SumLabeller())->label($balance);

        $expected = 0.0;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A59()
    {
        $name = 'Connectivity';

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->board;

        $balance = (new SanHeuristics(self::$function, $board->movetext(), $name))->getBalance();

        $label = (new SumLabeller())->label($balance);

        $expected = 2.0;

        $this->assertSame($expected, $label);
    }
}
