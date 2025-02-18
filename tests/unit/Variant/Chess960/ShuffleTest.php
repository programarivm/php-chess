<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\Shuffle;

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
