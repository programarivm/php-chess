<?php

namespace Chess\Tests\Unit\Image;

use Chess\Board;
use Chess\Image\BoardToSvg;
use Chess\Tests\AbstractUnitTestCase;

class BoardToSvgTest extends AbstractUnitTestCase
{
    const FILEPATH = __DIR__.'/../../data';

    /**
     * @test
     */
    public function output_foo_svg()
    {
        $board = new Board();

        (new BoardToSvg($board))->output(self::FILEPATH . '/foo.svg');

        // TODO

        $this->assertTrue(false);
    }
}
