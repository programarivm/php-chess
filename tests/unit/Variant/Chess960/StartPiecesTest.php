<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\StartPieces;
use Chess\Variant\Chess960\Shuffle;

class StartPiecesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $shuffle = (new Shuffle())->shuffle();
        $pieces = (new StartPieces($shuffle))->pieces();

        $this->assertSame(32, count($pieces));
    }
}
