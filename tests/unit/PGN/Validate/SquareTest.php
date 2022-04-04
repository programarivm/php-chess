<?php

namespace Chess\Tests\Unit\PGN\Validate;

use Chess\PGN\Validate;
use Chess\Tests\AbstractUnitTestCase;

class SquareTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function integer_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Validate::sq(9);
    }

    /**
     * @test
     */
    public function float_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Validate::sq(9.75);
    }

    /**
     * @test
     */
    public function a9_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Validate::sq('a9');
    }

    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Validate::sq('foo');
    }

    /**
     * @test
     */
    public function e4()
    {
        $this->assertSame(Validate::sq('e4'), 'e4');
    }
}
