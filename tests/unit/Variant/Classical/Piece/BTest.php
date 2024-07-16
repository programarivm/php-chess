<?php

namespace Chess\Tests\Unit\Variant\Classical\Piece;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\B;

class BTest extends AbstractUnitTestCase
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
        $bishop = new B('w', 'a2', self::$square);
        $mobility = [
            0 => [],
            1 => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            2 => [],
            3 => ['b1'],
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }

    /**
     * @test
     */
    public function mobility_d5()
    {
        $bishop = new B('w', 'd5', self::$square);
        $mobility = [
            0 => ['c6', 'b7', 'a8'],
            1 => ['e6', 'f7', 'g8'],
            2 => ['c4', 'b3', 'a2'],
            3 => ['e4', 'f3', 'g2', 'h1'],
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }

    /**
     * @test
     */
    public function mobility_a8()
    {
        $bishop = new B('w', 'a8', self::$square);
        $mobility = [
            0 => [],
            1 => [],
            2 => [],
            3 => ['b7', 'c6', 'd5', 'e4', 'f3', 'g2', 'h1'],
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }
}
