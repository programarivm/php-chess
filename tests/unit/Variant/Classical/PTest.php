<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Square;

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
        $flow = ['a3', 'a4'];
        $captureSquares = ['b3'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($captureSquares, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function white_d5()
    {
        $pawn = new P('w', 'd5', self::$square);

        $position = 'd5';
        $flow = ['d6'];
        $captureSquares = ['c6', 'e6'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($captureSquares, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function white_f7()
    {
        $pawn = new P('w', 'f7', self::$square);

        $position = 'f7';
        $flow = ['f8'];
        $captureSquares = ['e8', 'g8'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($captureSquares, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function white_f8()
    {
        $pawn = new P('w', 'f8', self::$square);

        $position = 'f8';
        $flow = [];
        $captureSquares = [];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($captureSquares, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function black_a2()
    {
        $pawn = new P('b', 'a2', self::$square);

        $position = 'a2';
        $flow = ['a1'];
        $captureSquares = ['b1'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($captureSquares, $pawn->xSqs);
    }

    /**
     * @test
     */
    public function black_d5()
    {
        $pawn = new P('b', 'd5', self::$square);

        $position = 'd5';
        $flow = ['d4'];
        $captureSquares = ['c4', 'e4'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($flow, $pawn->flow);
        $this->assertSame($captureSquares, $pawn->xSqs);
    }
}
