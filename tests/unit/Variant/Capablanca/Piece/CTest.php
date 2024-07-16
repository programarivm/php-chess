<?php

namespace Chess\Tests\Unit\Variant\Capablanca\Piece;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Capablanca\Piece\C;

class CTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function mobility_a1()
    {
        $chancellor = new C('w', 'a1', self::$square);
        $mobility = [
            0 => ['a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            1 => [],
            2 => [],
            3 => ['b1', 'c1', 'd1', 'e1', 'f1', 'g1', 'h1', 'i1', 'j1'],
            4 => ['c2', 'b3'],
        ];

        $this->assertEquals($mobility, $chancellor->mobility);
    }

    /**
     * @test
     */
    public function mobility_e4()
    {
        $chancellor = new C('w', 'e4', self::$square);
        $mobility = [
            0 => ['e5', 'e6', 'e7', 'e8'],
            1 => ['e3', 'e2', 'e1'],
            2 => ['d4', 'c4', 'b4', 'a4'],
            3 => ['f4', 'g4', 'h4', 'i4', 'j4'],
            4 => ['d6', 'c5', 'c3', 'd2', 'f2', 'g3', 'g5', 'f6'],
        ];

        $this->assertEquals($mobility, $chancellor->mobility);
    }

    /**
     * @test
     */
    public function mobility_d4()
    {
        $chancellor = new C('w', 'd4', self::$square);
        $mobility = [
            0 => ['d5', 'd6', 'd7', 'd8'],
            1 => ['d3', 'd2', 'd1'],
            2 => ['c4', 'b4', 'a4'],
            3 => ['e4', 'f4', 'g4', 'h4', 'i4', 'j4'],
            4 => ['c6', 'b5', 'b3', 'c2', 'e2', 'f3', 'f5', 'e6'],
        ];

        $this->assertEquals($mobility, $chancellor->mobility);
    }
}
