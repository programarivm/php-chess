<?php

namespace Chess\Tests\Unit\Variant\Dunsany;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Dunsany\Board;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(48, count($board->pieces()));
    }
}
