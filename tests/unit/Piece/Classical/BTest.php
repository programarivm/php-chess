<?php

namespace Chess\Tests\Unit\Piece\Classical;

use Chess\Piece\Classical\B;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;

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
            'upLeft' => [],
            'upRight' => ['b3', 'c4', 'd5', 'e6', 'f7', 'g8'],
            'downLeft' => [],
            'downRight' => ['b1']
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
            'upLeft' => ['c6', 'b7', 'a8'],
            'upRight' => ['e6', 'f7', 'g8'],
            'downLeft' => ['c4', 'b3', 'a2'],
            'downRight' => ['e4', 'f3', 'g2', 'h1']
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
            'upLeft' => [],
            'upRight' => [],
            'downLeft' => [],
            'downRight' => ['b7', 'c6', 'd5', 'e4', 'f3', 'g2', 'h1']
        ];

        $this->assertEquals($mobility, $bishop->mobility);
    }
}
