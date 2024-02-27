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
    }
}
