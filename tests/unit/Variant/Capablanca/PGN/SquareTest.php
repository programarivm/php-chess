<?php

namespace Chess\Tests\Unit\Variant\Capablanca\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\PGN\Square;

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
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate('foo');
    }

    /**
     * @test
     */
    public function k1_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate('k1');
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
    public function j1()
    {
        $this->assertSame(self::$square->validate('j1'), 'j1');
    }

    /**
     * @test
     */
    public function a1()
    {
        $this->assertSame(self::$square->validate('a1'), 'a1');
    }

    /**
     * @test
     */
    public function a10_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        self::$square->validate('a10');
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
    public function color_j8()
    {
        $this->assertSame(self::$square->color('j8'), 'b');
    }
}
