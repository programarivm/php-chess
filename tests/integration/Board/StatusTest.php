<?php

namespace PGNChess\Tests\Integration\PGN\File;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        if ($_ENV['APP_ENV'] !== 'test') {
            echo 'The integration tests can run on test environment only.' . PHP_EOL;
            exit;
        }
    }

    /**
     * @test
     */
    public function play_a3_h6_a4_h5_Ra2()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'a3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'h6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'a4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'h5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Ra2'));

        $this->assertNull($board->metadata());
    }

    /**
     * @test
     */
    public function play_d4_c6()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c6'));

        $this->assertTrue(is_array($board->metadata()));
        $this->assertNotNull($board->metadata());
    }

    /**
     * @test
     */
    public function play_d4_c6_c4()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'c4'));

        $this->assertTrue(is_array($board->metadata()));
        $this->assertNotNull($board->metadata());
    }
}
