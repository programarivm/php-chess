<?php

namespace Chess\Tests\Unit\Image;

use Chess\Board;
use Chess\FEN\StringToBoard;
use Chess\Image\BoardToGif;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\Benoni\FianchettoVariation as BenoniFianchettoVariation;
use Chess\Tests\Sample\Opening\RuyLopez\LucenaDefense;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;
use Chess\Tests\Sample\Opening\QueensGambit\SymmetricalDefense as QueensGambitSymmetricalDefense;

class BoardToGifTest extends AbstractUnitTestCase
{
    const OUTPUT_FOLDER = __DIR__.'/../../output';

    public static function tearDownAfterClass(): void
    {
        // unlink(self::OUTPUT_FOLDER . '/tmp.png');
    }

    /**
     * @test
     */
    public function output_benoni_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        (new BoardToGif($board))->output(self::OUTPUT_FOLDER, '/tmp.gif');

        // TODO
    }
}
