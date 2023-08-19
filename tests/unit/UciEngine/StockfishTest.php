<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\FenToBoard;
use Chess\Exception\StockfishException;
use Chess\UciEngine\Stockfish;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function bestMove()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $stockfish = (new Stockfish($board))
            ->setOptions([
                'Skill Level' => 9
            ])
            ->setParams([
                'depth' => 3
            ]);

        $fromFen = $board->toFen();
        $toFen = $stockfish->bestMove($fromFen);

        $this->assertNotEmpty($toFen);
    }

    /**
     * @test
     */
    public function play_e4()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $stockfish = (new Stockfish($board))
            ->setOptions([
                'Skill Level' => 17
            ])
            ->setParams([
                'depth' => 8
            ]);

        $lan = $stockfish->play($board->toFen());

        $this->assertTrue($board->playLan('b', $lan));
        $this->assertTrue($board->playLan('w', 'a2a3'));

        $lan = $stockfish->play($board->toFen());

        $this->assertTrue($board->playLan('b', $lan));
    }

    /**
     * @test
     */
    public function play_Na3()
    {
        $board = new Board();
        $board->play('w', 'Na3');

        $stockfish = (new Stockfish($board))
            ->setOptions([
                'Skill Level' => 17
            ])
            ->setParams([
                'depth' => 8
            ]);

        $lan = $stockfish->play($board->toFen());
        $this->assertTrue($board->playLan('b', $lan));
        $this->assertTrue($board->playLan('w', 'g1h3'));

        $lan = $stockfish->play($board->toFen());
        $this->assertTrue($board->playLan('b', $lan));
        $this->assertTrue($board->playLan('w', 'a1b1'));

        $lan = $stockfish->play($board->toFen());
        $this->assertTrue($board->playLan('b', $lan));
    }

    /**
     * @test
     */
    public function eval_start_throws_exception()
    {
        $this->expectException(StockfishException::class);

        $board = FenToBoard::create('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');

        $stockfish = new Stockfish($board);

        $stockfish->eval($board->toFen(), 'Total');
    }

    /**
     * @test
     */
    public function eval_start()
    {
        $board = FenToBoard::create('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');

        $stockfish = new Stockfish($board);

        $expected = 0.25;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_start()
    {
        $board = FenToBoard::create('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');

        $stockfish = new Stockfish($board);

        $expected = '$10';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_kaufman_01()
    {
        $board = FenToBoard::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - -');

        $stockfish = new Stockfish($board);

        $expected = -0.84;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_kaufman_01()
    {
        $board = FenToBoard::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - -');

        $stockfish = new Stockfish($board);

        $expected = '$17';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_kaufman_02()
    {
        $board = FenToBoard::create('3r2k1/p2r1p1p/1p2p1p1/q4n2/3P4/PQ5P/1P1RNPP1/3R2K1 b - -');

        $stockfish = new Stockfish($board);

        $expected = -0.67;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_kaufman_02()
    {
        $board = FenToBoard::create('3r2k1/p2r1p1p/1p2p1p1/q4n2/3P4/PQ5P/1P1RNPP1/3R2K1 b - -');

        $stockfish = new Stockfish($board);

        $expected = '$15';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_kaufman_03()
    {
        $board = FenToBoard::create('3r2k1/1p3ppp/2pq4/p1n5/P6P/1P6/1PB2QP1/1K2R3 w - -');

        $stockfish = new Stockfish($board);

        $expected = -1.02;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_kaufman_03()
    {
        $board = FenToBoard::create('3r2k1/1p3ppp/2pq4/p1n5/P6P/1P6/1PB2QP1/1K2R3 w - -');

        $stockfish = new Stockfish($board);

        $expected = '$17';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_kaufman_04()
    {
        $board = FenToBoard::create('r1b1r1k1/1ppn1p1p/3pnqp1/8/p1P1P3/5P2/PbNQNBPP/1R2RB1K w - -');

        $stockfish = new Stockfish($board);

        $expected = 0.35;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_kaufman_04()
    {
        $board = FenToBoard::create('r1b1r1k1/1ppn1p1p/3pnqp1/8/p1P1P3/5P2/PbNQNBPP/1R2RB1K w - -');

        $stockfish = new Stockfish($board);

        $expected = '$14';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_kaufman_05()
    {
        $board = FenToBoard::create('2r4k/pB4bp/1p4p1/6q1/1P1n4/2N5/P4PPP/2R1Q1K1 b - -');

        $stockfish = new Stockfish($board);

        $expected = -0.54;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_kaufman_05()
    {
        $board = FenToBoard::create('2r4k/pB4bp/1p4p1/6q1/1P1n4/2N5/P4PPP/2R1Q1K1 b - -');

        $stockfish = new Stockfish($board);

        $expected = '$15';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_kings()
    {
        $board = FenToBoard::create('8/8/8/5K2/8/2k5/8/8 w - -');

        $stockfish = new Stockfish($board);

        $expected = -0.03;

        $this->assertSame($expected, $stockfish->eval($board->toFen(), 'Final'));
    }

    /**
     * @test
     */
    public function eval_nag_kings()
    {
        $board = FenToBoard::create('8/8/8/5K2/8/2k5/8/8 w - -');

        $stockfish = new Stockfish($board);

        $expected = '$10';

        $this->assertSame($expected, $stockfish->evalNag($board->toFen(), 'Final'));
    }
}
