<?php

namespace Chess\Tests\Unit\Piece\Capablanca;

use Chess\Piece\Capablanca\A;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\PGN\AN\Square;

class ATest extends AbstractUnitTestCase
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
        $archbishop = new A('w', 'a1', self::$square);

        $mobility = [
            'upLeft' => [],
            'upRight' => ['b2', 'c3', 'd4', 'e5', 'f6', 'g7', 'h8'],
            'downLeft' => [],
            'downRight' => [],
            'knight' => ['c2', 'b3']
        ];

        $this->assertEquals($mobility, $archbishop->mobility);
    }

    /**
     * @test
     */
    public function mobility_e4()
    {
        $archbishop = new A('w', 'e4', self::$square);

        $mobility = [
            'upLeft' => ['d5', 'c6', 'b7', 'a8'],
            'upRight' => ['f5', 'g6', 'h7', 'i8'],
            'downLeft' => ['d3', 'c2', 'b1'],
            'downRight' => ['f3', 'g2', 'h1'],
            'knight' => ['d6', 'c5', 'c3', 'd2', 'f2', 'g3', 'g5', 'f6']
        ];

        $this->assertEquals($mobility, $archbishop->mobility);

    }

    /**
     * @test
     */
    public function mobility_d4()
    {
        $archbishop = new A('w', 'd4', self::$square);

        $mobility = [
            'upLeft' => ['c5', 'b6', 'a7'],
            'upRight' => ['e5', 'f6', 'g7', 'h8'],
            'downLeft' => ['c3', 'b2', 'a1'],
            'downRight' => ['e3', 'f2', 'g1'],
            'knight' => ['c6', 'b5', 'b3', 'c2', 'e2', 'f3', 'f5', 'e6']
        ];

        $this->assertEquals($mobility, $archbishop->mobility);

    }
}
