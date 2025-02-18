<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\RandomPieceArrayFactory;
use Chess\Variant\Chess960\Shuffle;

class RandomPieceArrayFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $pieces = RandomPieceArrayFactory::create((new Shuffle())->shuffle());

        $this->assertSame(32, count($pieces));
    }
}
