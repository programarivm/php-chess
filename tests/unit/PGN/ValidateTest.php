<?php

namespace PGNChess\Tests\Unit\PGN;

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
    public function tag_Foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Validate::tag('Foo');
    }

    /**
     * @test
     */
    public function tag_Event_Vladimir_Dvorkovich_Cup()
    {
        $tag = Validate::tag('[Event "Vladimir Dvorkovich Cup"]');

        $this->assertEquals('Event', $tag->name);
        $this->assertEquals('Vladimir Dvorkovich Cup', $tag->value);
    }

    /**
     * @test
     */
    public function tag_Site_Saint_Louis_USA()
    {
        $tag = Validate::tag('[Site "Saint Louis USA"]');

        $this->assertEquals('Site', $tag->name);
        $this->assertEquals('Saint Louis USA', $tag->value);
    }

    /**
     * @test
     */
    public function tag_Date_2018_05_10()
    {
        $tag = Validate::tag('[Date "2018.05.10"]');

        $this->assertEquals('Date', $tag->name);
        $this->assertEquals('2018.05.10', $tag->value);
    }

    /**
     * @test
     */
    public function tag_Round_9_6()
    {
        $tag = Validate::tag('[Round "9.6"]');

        $this->assertEquals('Round', $tag->name);
        $this->assertEquals('9.6', $tag->value);
    }

    /**
     * @test
     */
    public function tag_White_Kantor_Gergely()
    {
        $tag = Validate::tag('[White "Kantor, Gergely"]');

        $this->assertEquals('White', $tag->name);
        $this->assertEquals('Kantor, Gergely', $tag->value);
    }

    /**
     * @test
     */
    public function tag_Black_Gelfand_Boris()
    {
        $tag = Validate::tag('[Black "Gelfand, Boris"]');

        $this->assertEquals('Black', $tag->name);
        $this->assertEquals('Gelfand, Boris', $tag->value);
    }

    /**
     * @test
     */
    public function tag_Result_12_12()
    {
        $tag = Validate::tag('[Result "1/2-1/2"]');

        $this->assertEquals('Result', $tag->name);
        $this->assertEquals('1/2-1/2', $tag->value);
    }

    /**
     * @test
     */
    public function tag_WhiteElo_2579()
    {
        $tag = Validate::tag('[WhiteElo "2579"]');

        $this->assertEquals('WhiteElo', $tag->name);
        $this->assertEquals('2579', $tag->value);
    }

    /**
     * @test
     */
    public function tag_BlackElo_2474()
    {
        $tag = Validate::tag('[BlackElo "2474"]');

        $this->assertEquals('BlackElo', $tag->name);
        $this->assertEquals('2474', $tag->value);
    }

    /**
     * @test
     */
    public function tag_ECO_D35()
    {
        $tag = Validate::tag('[ECO "D35"]');

        $this->assertEquals('ECO', $tag->name);
        $this->assertEquals('D35', $tag->value);
    }

    /**
     * @test
     */
    public function tag_EventDate_2017_12_17()
    {
        $tag = Validate::tag('[EventDate "2017.12.17"]');

        $this->assertEquals('EventDate', $tag->name);
        $this->assertEquals('2017.12.17', $tag->value);
    }
}
