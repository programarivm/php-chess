<?php

namespace Chess\Tests\Unit\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\Str;
use Chess\Tests\AbstractUnitTestCase;

class StrTest extends AbstractUnitTestCase
{
    static private Str $fenStr;

    public static function setUpBeforeClass(): void
    {
        self::$fenStr = new Str();
    }

    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$fenStr->validate('foo');
    }

    /**
     * @test
     */
    public function kaufman_01_piece_placement_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$fenStr->validate('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP w - - bm Nf6+');
    }

    /**
     * @test
     */
    public function kaufman_01_with_comment()
    {
        $string = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+; id "position 01";';

        $this->assertSame($string, self::$fenStr->validate($string));
    }

    /**
     * @test
     */
    public function kaufman_01()
    {
        $string = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+';

        $this->assertSame($string, self::$fenStr->validate($string));
    }

    /**
     * @test
     */
    public function to_ascii_array_start()
    {
        $piecePlacement = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR';

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, self::$fenStr->toArray($piecePlacement));
    }
}
