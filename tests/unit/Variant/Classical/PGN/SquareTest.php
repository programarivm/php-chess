<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\Square;
use Chess\Tests\AbstractUnitTestCase;

class SquareTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function integer_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate(9);
    }

    /**
     * @test
     */
    public function float_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate(9.75);
    }

    /**
     * @test
     */
    public function a9_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate('a9');
    }

    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate('foo');
    }

    /**
     * @test
     */
    public function e4()
    {
        $this->assertSame(self::$square->validate('e4'), 'e4');
    }

    /**
     * @test
     */
    public function color_a1()
    {
        $this->assertSame(self::$square->color('a1'), 'b');
    }

    /**
     * @test
     */
    public function color_a2()
    {
        $this->assertSame(self::$square->color('a2'), 'w');
    }

    /**
     * @test
     */
    public function color_b1()
    {
        $this->assertSame(self::$square->color('b1'), 'w');
    }

    /**
     * @test
     */
    public function color_b2()
    {
        $this->assertSame(self::$square->color('b2'), 'b');
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_0_0()
    {
        $this->assertSame('a1', self::$square->toAlgebraic(0, 0));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_0_7()
    {
        $this->assertSame('a8', self::$square->toAlgebraic(0, 7));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_0_8()
    {
        $this->assertSame('a9', self::$square->toAlgebraic(0, 8));
    }

    /**
     * @test
     */
    public function is_diagonal_line_b4_c5_d6()
    {
        $this->assertTrue(self::$square->isDiagonalLine(['b4', 'c5', 'd6']));
    }

    /**
     * @test
     */
    public function is_diagonal_line_b4_d6_c5()
    {
        $this->assertTrue(self::$square->isDiagonalLine(['b4', 'd6', 'c5']));
    }

    /**
     * @test
     */
    public function is_diagonal_line_b4_c3_d2()
    {
        $this->assertTrue(self::$square->isDiagonalLine(['b4', 'c3', 'd2']));
    }

    /**
     * @test
     */
    public function is_diagonal_line_b4_d2()
    {
        $this->assertFalse(self::$square->isDiagonalLine(['b4', 'd2']));
    }

    /**
     * @test
     */
    public function is_diagonal_line_b4_c4()
    {
        $this->assertFalse(self::$square->isDiagonalLine(['b4', 'c4']));
    }
}
