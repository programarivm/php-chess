<?php

namespace Chess\Tests\Unit\Fen;

use Chess\FEN\ShortenedStringToPgn;
use Chess\Tests\AbstractUnitTestCase;

class ShortenedStringToPgnTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4()
    {
        $pgn = (new ShortenedStringToPgn(
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'
        ))->create();

        $expected = [
            'w' => 'e4',
        ];

        $this->assertSame($expected, $pgn);
    }

    /**
     * @test
     */
    public function Nf3()
    {
        $pgn = (new ShortenedStringToPgn(
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/8/5N2/PPPPPPPP/RNBQKB1R b'
        ))->create();

        $expected = [
            'w' => 'Nf3',
        ];

        $this->assertSame($expected, $pgn);
    }

    /**
     * @test
     */
    public function kaufman_01_play_Qg4()
    {
        $pgn = (new ShortenedStringToPgn(
            '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - -',
            '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN1Q1/P2B4/1P3PPP/2R2R1K b'
        ))->create();

        $expected = [
            'w' => 'Qg4',
        ];

        $this->assertSame($expected, $pgn);
    }
}
