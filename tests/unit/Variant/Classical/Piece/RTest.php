<?php

namespace Chess\Tests\Unit\Variant\Classical\Piece;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\RType;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\R;

class RTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function mobility_a2()
    {
        $rook = new R('w', 'a2', self::$square, RType::R);
        $mobility = [
            0 => ['a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            1 => ['a1'],
            2 => [],
            3 => ['b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],
        ];

        $this->assertEquals($mobility, $rook->mobility);
    }

    /**
     * @test
     */
    public function mobility_d5()
    {
        $rook = new R('w', 'd5', self::$square, RType::R);
        $mobility = [
            0 => ['d6', 'd7', 'd8'],
            1 => ['d4', 'd3', 'd2', 'd1'],
            2 => ['c5', 'b5', 'a5'],
            3 => ['e5', 'f5', 'g5', 'h5'],
        ];

        $this->assertEquals($mobility, $rook->mobility);
    }
}
