<?php

namespace Chess\Tests\Unit\UciEngine\Details;

use Chess\Tests\AbstractUnitTestCase;
use Chess\UciEngine\Details\Limit;

class LimitTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function instantiation()
    {
        $limit = new Limit();

        $this->assertTrue(is_a($limit, Limit::class));
        $this->assertSame(null, $limit->time);
        $this->assertSame(null, $limit->depth);
        $this->assertSame(null, $limit->nodes);
        $this->assertSame(null, $limit->mate);
        $this->assertSame(null, $limit->white_clock);
        $this->assertSame(null, $limit->black_clock);
        $this->assertSame(null, $limit->white_inc);
        $this->assertSame(null, $limit->black_inc);
        $this->assertSame(null, $limit->remaining_moves);
    }

    /**
     * @test
     */
    public function time_3000()
    {
        $limit = new Limit();
        $limit->time = 3000;

        $this->assertSame(3000, $limit->time);
    }
}
