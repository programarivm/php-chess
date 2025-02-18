<?php

namespace Chess\Tests\Unit\Variant\CapablancaFischer;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\CapablancaFischer\Shuffle;

class ShuffleTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $arr = (new Shuffle())->shuffle();

        $this->assertNotEmpty($arr);
    }
}
