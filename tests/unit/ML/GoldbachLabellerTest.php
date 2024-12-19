<?php

namespace Chess\Tests\Unit\ML;

use Chess\FenHeuristics;
use Chess\Function\CompleteFunction;
use Chess\ML\GoldbachLabeller;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;

class GoldbachLabellerTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function A00()
    {
        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->board;

        $balance = (new FenHeuristics(self::$function, $board))->balance;

        $label = (new GoldbachLabeller())->label($balance);

        $expected = -11208696778;

        $this->assertSame($expected, $label);
    }
}
