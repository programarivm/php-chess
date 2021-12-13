<?php

namespace Chess\Tests\Unit\PGN\Validate;

use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\Tests\AbstractUnitTestCase;

class ColorTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function green_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Validate::color('green');
    }

    /**
     * @test
     */
    public function white()
    {
        $this->assertSame(Symbol::WHITE, Validate::color('w'));
    }

    /**
     * @test
     */
    public function black()
    {
        $this->assertSame(Symbol::BLACK, Validate::color('b'));
    }
}
