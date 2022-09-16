<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\StartingPieces;

class StartingPiecesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function create()
    {
        $pieces = (new StartingPieces())->create();

        $this->assertSame(16, count($pieces));
    }
}
