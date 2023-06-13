<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Piece\C;

class CTest extends AbstractUnitTestCase
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
        $chancellor = new C('w', 'a1', self::$size);
        $mobility = (object) [
            'up' => ['a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8'],
            'down' => [],
            'left' => [],
            'right' => ['b1', 'c1', 'd1', 'e1', 'f1', 'g1', 'h1', 'i1', 'j1'],
            'knight' => ['c2', 'b3'],
        ];

        $this->assertEquals($mobility, $chancellor->getMobility());
    }

    /**
     * @test
     */
    public function mobility_e4()
    {
        $chancellor = new C('w', 'e4', self::$size);
        $mobility = (object) [
            'up' => ['e5', 'e6', 'e7', 'e8'],
            'down' => ['e3', 'e2', 'e1'],
            'left' => ['d4', 'c4', 'b4', 'a4'],
            'right' => ['f4', 'g4', 'h4', 'i4', 'j4'],
            'knight' => ['d6', 'c5', 'c3', 'd2', 'f2', 'g3', 'g5', 'f6'],
        ];

        $this->assertEquals($mobility, $chancellor->getMobility());
    }

    /**
     * @test
     */
    public function mobility_d4()
    {
        $chancellor = new C('w', 'd4', self::$size);
        $mobility = (object) [
            'up' => ['d5', 'd6', 'd7', 'd8'],
            'down' => ['d3', 'd2', 'd1'],
            'left' => ['c4', 'b4', 'a4'],
            'right' => ['e4', 'f4', 'g4', 'h4', 'i4', 'j4'],
            'knight' => ['c6', 'b5', 'b3', 'c2', 'e2', 'f3', 'f5', 'e6'],
        ];

        $this->assertEquals($mobility, $chancellor->getMobility());
    }
}
