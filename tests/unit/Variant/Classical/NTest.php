<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\N;

class NTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function flow_d4()
    {
        $knight = new N('w', 'd4', self::$square);
        $flow = [
            'c6',
            'b5',
            'b3',
            'c2',
            'e2',
            'f3',
            'f5',
            'e6'
        ];

        $this->assertSame($flow, $knight->flow);
    }

    /**
     * @test
     */
    public function flow_h1()
    {
        $knight = new N('w', 'h1', self::$square);
        $flow = [
            'g3',
            'f2'
        ];

        $this->assertSame($flow, $knight->flow);
    }

    /**
     * @test
     */
    public function flow_b1()
    {
        $knight = new N('w', 'b1', self::$square);
        $flow = [
            'a3',
            'd2',
            'c3'
        ];

        $this->assertSame($flow, $knight->flow);
    }
}
