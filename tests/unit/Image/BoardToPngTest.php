<?php

namespace Chess\Tests\Unit\Image;

use Chess\Board;
use Chess\Image\BoardToPng;
use Chess\Tests\AbstractUnitTestCase;

class BoardToPngTest extends AbstractUnitTestCase
{
    const FILEPATH = __DIR__.'/../../data';

    /**
     * @test
     */
    public function output_foo_png()
    {
        $board = new Board();

        (new BoardToPng($board))->output(self::FILEPATH . '/foo.png');

        // TODO

        $this->assertTrue(false);
    }
}
