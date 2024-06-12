<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Piece\P;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;

class PTest extends AbstractUnitTestCase
{
    static private $square;

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
        $mobility = ['a3', 'a4'];
        $captureSquares = ['b3'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($mobility, $pawn->mobility);
        $this->assertSame($captureSquares, $pawn->getCaptureSqs());
    }

    /**
     * @test
     */
    public function white_d5()
    {
        $pawn = new P('w', 'd5', self::$square);

        $position = 'd5';
        $mobility = ['d6'];
        $captureSquares = ['c6', 'e6'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($mobility, $pawn->mobility);
        $this->assertSame($captureSquares, $pawn->getCaptureSqs());
    }

    /**
     * @test
     */
    public function white_f7()
    {
        $pawn = new P('w', 'f7', self::$square);

        $position = 'f7';
        $mobility = ['f8'];
        $captureSquares = ['e8', 'g8'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($mobility, $pawn->mobility);
        $this->assertSame($captureSquares, $pawn->getCaptureSqs());
    }

    /**
     * @test
     */
    public function white_f8()
    {
        $pawn = new P('w', 'f8', self::$square);

        $position = 'f8';
        $mobility = [];
        $captureSquares = [];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($mobility, $pawn->mobility);
        $this->assertSame($captureSquares, $pawn->getCaptureSqs());
    }

    /**
     * @test
     */
    public function black_a2()
    {
        $pawn = new P('b', 'a2', self::$square);

        $position = 'a2';
        $mobility = ['a1'];
        $captureSquares = ['b1'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($mobility, $pawn->mobility);
        $this->assertSame($captureSquares, $pawn->getCaptureSqs());
    }

    /**
     * @test
     */
    public function black_d5()
    {
        $pawn = new P('b', 'd5', self::$square);

        $position = 'd5';
        $mobility = ['d4'];
        $captureSquares = ['c4', 'e4'];

        $this->assertSame($position, $pawn->sq);
        $this->assertEquals($mobility, $pawn->mobility);
        $this->assertSame($captureSquares, $pawn->getCaptureSqs());
    }
}
