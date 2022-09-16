<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\Board;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function getPieces()
    {
        $board = new Board();

        $pieces = $board->getPieces();

        $this->assertSame(32, count($pieces));
    }
}
