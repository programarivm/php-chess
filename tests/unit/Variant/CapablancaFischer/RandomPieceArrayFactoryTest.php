<?php

namespace Chess\Tests\Unit\Variant\CapablancaFischer;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\CapablancaFischer\RandomPieceArrayFactory;
use Chess\Variant\CapablancaFischer\Shuffle;

class RandomPieceArrayFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $pieces = RandomPieceArrayFactory::create((new Shuffle())->shuffle());

        $this->assertSame(40, count($pieces));
    }
}
