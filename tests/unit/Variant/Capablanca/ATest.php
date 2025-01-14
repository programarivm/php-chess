<?php

namespace Chess\Tests\Unit\Variant\Capablanca;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Capablanca\A;

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
    public function flow_a1()
    {
        $archbishop = new A('w', 'a1', self::$square);

        $flow = [
            0 => [],
            1 => ['b2', 'c3', 'd4', 'e5', 'f6', 'g7', 'h8'],
            2 => [],
            3 => [],
            4 => ['c2', 'b3'],
        ];

        $this->assertEquals($flow, $archbishop->flow);
    }

    /**
     * @test
     */
    public function flow_e4()
    {
        $archbishop = new A('w', 'e4', self::$square);

        $flow = [
            0 => ['d5', 'c6', 'b7', 'a8'],
            1 => ['f5', 'g6', 'h7', 'i8'],
            2 => ['d3', 'c2', 'b1'],
            3 => ['f3', 'g2', 'h1'],
            4 => ['d6', 'c5', 'c3', 'd2', 'f2', 'g3', 'g5', 'f6'],
        ];

        $this->assertEquals($flow, $archbishop->flow);

    }

    /**
     * @test
     */
    public function flow_d4()
    {
        $archbishop = new A('w', 'd4', self::$square);

        $flow = [
            0 => ['c5', 'b6', 'a7'],
            1 => ['e5', 'f6', 'g7', 'h8'],
            2 => ['c3', 'b2', 'a1'],
            3 => ['e3', 'f2', 'g1'],
            4 => ['c6', 'b5', 'b3', 'c2', 'e2', 'f3', 'f5', 'e6'],
        ];

        $this->assertEquals($flow, $archbishop->flow);

    }
}
