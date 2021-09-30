<?php

namespace Chess\Tests\Unit\Image;

use Chess\Board;
use Chess\Image\BoardToPng;
use Chess\Tests\AbstractUnitTestCase;

class BoardToPngTest extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../output';

    /**
     * @test
     */
    public function output_01_starting()
    {
        $board = new Board();

        (new BoardToPng($board))->output(self::OUTPUT_FOLDER . '/tmp.png');

        $this->assertEquals(
            md5(file_get_contents(self::OUTPUT_FOLDER . '/tmp.png')),
            md5(file_get_contents(self::DATA_FOLDER . '/img/01_starting.png'))
        );
    }
}
