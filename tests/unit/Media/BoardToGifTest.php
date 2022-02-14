<?php

namespace Chess\Tests\Unit\Media;

use Chess\Board;
use Chess\Media\BoardToGif;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\FianchettoVariation as BenoniFianchettoVariation;

class BoardToGifTest extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../output';

    public static function tearDownAfterClass(): void
    {
        array_map('unlink', glob(self::OUTPUT_FOLDER . '/*.gif'));
    }

    /**
     * @test
     */
    public function folder_does_not_exist()
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $filename = (new BoardToGif($board))->output('foo');
    }

    /**
     * @test
     */
    public function output_benoni_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $filename = (new BoardToGif($board))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER.'/gif/benoni_fianchetto_variation.gif')
        );
    }

    /**
     * @test
     */
    public function output_benoni_fianchetto_variation_flip()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $filename = (new BoardToGif($board, $flip = true))->output(self::OUTPUT_FOLDER);

        $this->assertSame(
            md5_file(self::OUTPUT_FOLDER.'/'.$filename),
            md5_file(self::DATA_FOLDER . '/gif/benoni_fianchetto_variation_flip.gif')
        );
    }
}
