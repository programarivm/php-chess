<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Piece\N;
use Chess\Variant\Classical\PGN\AN\Square;

class NTest extends AbstractUnitTestCase
{
    static private $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function mobility_d4()
    {
        $knight = new N('w', 'd4', self::$square);
        $mobility = [
            'c6',
            'b5',
            'b3',
            'c2',
            'e2',
            'f3',
            'f5',
            'e6'
        ];

        $this->assertSame($mobility, $knight->mobility);
    }

    /**
     * @test
     */
    public function mobility_h1()
    {
        $knight = new N('w', 'h1', self::$square);
        $mobility = [
            'g3',
            'f2'
        ];

        $this->assertSame($mobility, $knight->mobility);
    }

    /**
     * @test
     */
    public function mobility_b1()
    {
        $knight = new N('w', 'b1', self::$square);
        $mobility = [
            'a3',
            'd2',
            'c3'
        ];

        $this->assertSame($mobility, $knight->mobility);
    }
}
