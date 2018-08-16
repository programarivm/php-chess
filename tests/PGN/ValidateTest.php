<?php

namespace PGNChess\Tests\PGN;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    /**
     * @test
     */
    public function color_green_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::color('green');
    }

    /**
     * @test
     */
    public function color_white()
    {
        $this->assertEquals(Symbol::WHITE, Validate::color(Symbol::WHITE));
    }

    /**
     * @test
     */
    public function color_black()
    {
        $this->assertEquals(Symbol::BLACK, Validate::color(Symbol::BLACK));
    }

    /**
     * @test
     */
    public function square_integer_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square(9);
    }

    /**
     * @test
     */
    public function square_float_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square(9.75);
    }

    /**
     * @test
     */
    public function square_a9_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square('a9');
    }

    /**
     * @test
     */
    public function square_foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::square('foo');
    }

    /**
     * @test
     */
    public function square_e4()
    {
        $this->assertEquals(Validate::square('e4'), 'e4');
    }

    /**
     * @test
     */
    public function tag_foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validate::tag('foo');
    }

    /**
     * @test
     */
    public function tag_event_Sharjah_Rapid_2018()
    {
        $tag = Validate::tag('[Event "Sharjah Rapid 2018"]');

        $this->assertEquals('Event', $tag->name);
        $this->assertEquals('Sharjah Rapid 2018', $tag->value);
    }

    /**
     * @test
     */
    public function tag_event_Vladimir_Dvorkovich_Cup()
    {
        $tag = Validate::tag('[Event "Vladimir Dvorkovich Cup"]');

        $this->assertEquals('Event', $tag->name);
        $this->assertEquals('Vladimir Dvorkovich Cup', $tag->value);
    }

    /**
     * @test
     */
    public function tag_site_Pleven_BUL()
    {
        $tag = Validate::tag('[Site "Pleven BUL"]');

        $this->assertEquals('Site', $tag->name);
        $this->assertEquals('Pleven BUL', $tag->value);
    }
}
