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
        $expected = [
            "These pieces are hanging: The rook on a1, the rook on h1, the rook on a8, the rook on h8, the pawn on c5.",
        ];

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
        $expected = [
            "These pieces are hanging: The rook on a8, Black's archbishop on c8, Black's chancellor on h8, the rook on j8, the pawn on i7, the pawn on f4, the pawn on i2, the rook on a1, White's archbishop on c1, White's chancellor on h1, the rook on j1.",
        ];

        $board = FenToBoardFactory::create(
            'rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3',
            new CapablancaBoard()
        );

        $paragraph = (new FenElaboration(self::$function, $board))->paragraph;

        $this->assertSame($expected, $paragraph);
    }
}
