<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Play\SanPlay;
use Chess\Tutor\FenElaboration;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;

class FenElaborationTest extends AbstractUnitTestCase
{
    static private CompleteFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new CompleteFunction();
    }

    /**
     * @test
     */
    public function A08()
    {
        $expected = [];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->board;

        $paragraph = (new FenElaboration(self::$function, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expected = [
            "The knight on e6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
            "Black's king on f7 can be checked so it is vulnerable to forcing moves.",
        ];

        $board = FenToBoardFactory::create('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1');

        $paragraph = (new FenElaboration(self::$function, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function capablanca_f4()
    {
        $expected = [];

        $board = FenToBoardFactory::create(
            'rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3',
            new CapablancaBoard()
        );

        $paragraph = (new FenElaboration(self::$function, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }
}
