<?php

namespace Chess\Tests\Unit\Variant\Dunsany;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Dunsany\P;

class PTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function white_a2()
    {
        $pawn = new P('w', 'a2', self::$square);

        $position = 'a2';
        $flow = ['a3'];
        $xSqs = ['b3'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($xSqs, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function white_d5()
    {
        $pawn = new P('w', 'd5', self::$square);

        $position = 'd5';
        $flow = ['d6'];
        $xSqs = ['c6', 'e6'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($xSqs, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function white_f7()
    {
        $pawn = new P('w', 'f7', self::$square);

        $position = 'f7';
        $flow = ['f8'];
        $xSqs = ['e8', 'g8'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($xSqs, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function white_f8()
    {
        $pawn = new P('w', 'f8', self::$square);

        $position = 'f8';
        $flow = [];
        $xSqs = [];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($xSqs, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function black_a2()
    {
        $pawn = new P('b', 'a2', self::$square);

        $position = 'a2';
        $flow = ['a1'];
        $xSqs = ['b1'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($xSqs, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function black_d5()
    {
        $pawn = new P('b', 'd5', self::$square);

        $position = 'd5';
        $flow = ['d4'];
        $xSqs = ['c4', 'e4'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($xSqs, $pawn->xSqs);
    }
}
