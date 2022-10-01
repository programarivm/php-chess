<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Piece\A;

class ATest extends AbstractUnitTestCase
{
    static private $size;

    public static function setUpBeforeClass(): void
    {
        self::$size = [
            'files' => 10,
            'ranks' => 10,
        ];
    }

    /**
     * @test
     */
    public function mobility_a1()
    {
        $archbishop = new A('w', 'a1', self::$size);

        // TODO ...

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function mobility_e4()
    {
        $archbishop = new A('w', 'e4', self::$size);

        $mobility = (object) [
            'upLeft' => ['d5', 'c6', 'b7', 'a8'],
            'upRight' => ['f5', 'g6', 'h7', 'i8', 'j9'],
            'downLeft' => ['d3', 'c2', 'b1'],
            'downRight' => ['f3', 'g2', 'h1'],
            'knight' => ['d6', 'c5', 'c3', 'd2', 'f2', 'g3', 'g5', 'f6']
        ];

        $this->assertEquals($mobility, $archbishop->getMobility());

    }

    /**
     * @test
     */
    public function mobility_d4()
    {
        $archbishop = new A('w', 'd4', self::$size);

        // TODO ...

        $this->assertTrue(true);
    }
}
