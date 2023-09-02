<?php

namespace Chess\Tests\Unit\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class StrToBoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        (new StrToBoard('foo'))->create();
    }

    /**
     * @test
     */
    public function foo_bar_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        (new StrToBoard('foo bar'))->create();
    }

    /**
     * @test
     */
    public function no_kings_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = (new StrToBoard('8/8/5r1R/8/7p/8/8/8 b - - 0 1'))->create();
    }

    /**
     * @test
     */
    public function eT_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = (new StrToBoard('7K/8/5e1T/8/7k/8/8/8 b - - 0 1'))->create();
    }

    /**
     * @test
     */
    public function x_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = (new StrToBoard('7x/8/5k1K/8/7p/8/8/8 b - - 0 1'))->create();
    }

    /**
     * @test
     */
    public function y_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = (new StrToBoard('7y/8/5k1K/8/7p/8/8/8 b - - 0 1'))->create();
    }

    /**
     * @test
     */
    public function nine_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = (new StrToBoard('7k/9/8/8/8/8/2K5/r7 w - - 0 1'))->create();
    }

    /**
     * @test
     */
    public function e4()
    {
        $board = (new StrToBoard('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5()
    {
        $board = (new StrToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_flip()
    {
        $board = (new StrToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $expected = [
            7 => [' R ', ' N ', ' B ', ' K ', ' Q ', ' B ', ' N ', ' R '],
            6 => [' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ', ' P '],
            5 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            4 => [' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ', ' . '],
            3 => [' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . '],
            2 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            1 => [' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ', ' p '],
            0 => [' r ', ' n ', ' b ', ' k ', ' q ', ' b ', ' n ', ' r '],
        ];

        $this->assertSame($expected, $board->toAsciiArray(true));
    }

    /**
     * @test
     */
    public function A59()
    {
        $board = (new StrToBoard('rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' n ', ' . ', ' q ', ' k ', ' b ', ' . ', ' r ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' . ', ' . ', ' P ', ' . ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' . ', ' K ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange()
    {
        $board = (new StrToBoard('r1b1kbnr/1pp2ppp/p1p5/8/3NP3/8/PPP2PPP/RNB1K2R b KQkq - 0 7'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' . ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' . ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' p ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' N ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' . ', ' K ', ' . ', ' . ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function closed_sicilian()
    {
        $board = (new StrToBoard('r1bqk1nr/pp2ppbp/2np2p1/2p5/4P3/2NP2P1/PPP2PBP/R1BQK1NR w KQkq - 0 6'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' . ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' b ', ' p ' ],
            5 => [ ' . ', ' . ', ' n ', ' p ', ' . ', ' . ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' P ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' B ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' K ', ' . ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function closed_sicilian_flip()
    {
        $board = (new StrToBoard('r1bqk1nr/pp2ppbp/2np2p1/2p5/4P3/2NP2P1/PPP2PBP/R1BQK1NR w KQkq - 0 6'))
            ->create();

        $expected = [
            7 => [' R ', ' N ', ' . ', ' K ', ' Q ', ' B ', ' . ', ' R '],
            6 => [' P ', ' B ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P '],
            5 => [' . ', ' P ', ' . ', ' . ', ' P ', ' N ', ' . ', ' . '],
            4 => [' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ', ' . '],
            3 => [' . ', ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . '],
            2 => [' . ', ' p ', ' . ', ' . ', ' p ', ' n ', ' . ', ' . '],
            1 => [' p ', ' b ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p '],
            0 => [' r ', ' n ', ' . ', ' k ', ' q ', ' b ', ' . ', ' r '],
        ];

        $this->assertSame($expected, $board->toAsciiArray(true));
    }

    /**
     * @test
     */
    public function e4_e5_play_Nf3_Nc6()
    {
        $board = (new StrToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' n ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' . ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_play_Nc6()
    {
        $board = (new StrToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $this->assertFalse($board->play('w', 'Nc6'));
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5()
    {
        $board = (new StrToBoard('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w KQkq f6 0 3'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' . ', ' p ', ' p ', ' . ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' . ', ' P ', ' p ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $legalMoves = $board->getPieceBySq('e5')->sqs();

        $this->assertSame($expected, $array);
        $this->assertSame($legalMoves, ['e6' , 'f6']);
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5_play_exf6()
    {
        $board = (new StrToBoard('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w KQkq f6 0 3'))
            ->create();

        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function kaufman_01()
    {
        $board = (new StrToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' . ', ' r ', ' b ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' b ', ' . ', ' n ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' p ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' B ', ' . ', ' p ', ' N ', ' . ', ' . ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' B ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' R ', ' Q ', ' . ', ' R ', ' . ', ' K ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4()
    {
        $board = (new StrToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $board->play('w', 'Qg4');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' . ', ' r ', ' b ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' b ', ' . ', ' n ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' p ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' B ', ' . ', ' p ', ' N ', ' . ', ' Q ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' B ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' R ', ' . ', ' . ', ' R ', ' . ', ' K ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4_a5()
    {
        $board = (new StrToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $board->play('w', 'Qg4');
        $board->play('b', 'a5');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' . ', ' r ', ' b ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' . ', ' . ', ' b ', ' . ', ' n ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' p ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' p ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' B ', ' . ', ' p ', ' N ', ' . ', ' Q ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' B ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' R ', ' . ', ' . ', ' R ', ' . ', ' K ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4_then_get_piece()
    {
        $board = (new StrToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $board->play('w', 'Qg4');

        $legalMoves = $board->getPieceBySq('a7')->sqs();

        $expected = ['a6', 'a5'];

        $this->assertSame($expected, $legalMoves);
    }

    /**
     * @test
     */
    public function endgame_king_and_rook_vs_king_cannot_capture()
    {
        $board = (new StrToBoard('8/5k2/8/8/2K1p3/3r4/8/8 w - - 0 1'))
            ->create();

        $legalMoves = $board->getPieceBySq('c4')->sqs();

        $expected = ['c5', 'b4', 'b5'];

        $this->assertSame($expected, $legalMoves);
    }

    /**
     * @test
     */
    public function endgame_king_and_rook_vs_king_can_capture()
    {
        $board = (new StrToBoard('8/5k2/8/8/2K5/3rp3/8/8 w - - 0 1'))
            ->create();

        $legalMoves = $board->getPieceBySq('c4')->sqs();

        $expected = ['c5', 'b4', 'b5', 'd3'];

        $this->assertSame($expected, $legalMoves);
    }

    /**
     * @test
     */
    public function endgame_checkmate_king_and_rook_vs_king()
    {
        $board = (new StrToBoard('7k/8/8/8/8/8/2K5/r7 w - - 0 1'))
            ->create();

        $legalMoves = $board->getPieceBySq('c2')->sqs();

        $expected = ['c3', 'b2', 'd2', 'b3', 'd3'];

        $this->assertSame($expected, $legalMoves);
    }

    /**
     * @test
     */
    public function endgame_checkmate_king_and_rook_vs_king_validate()
    {
        $board = (new StrToBoard('7k/8/8/8/8/8/2K5/r7 w - - 0 1'))
            ->create();

        $this->assertTrue($board->play('w', 'Kb2'));
        $this->assertTrue($board->play('b', 'Kg7'));
        $this->assertTrue($board->play('w', 'Kxa1'));
    }

    /**
     * @test
     */
    public function endgame_checkmate_play_and_get_start_fen()
    {
        $board = (new StrToBoard('8/8/R7/8/4Q3/rk3K2/8/8 w - -'))
            ->create();

        $this->assertTrue($board->play('w', 'Qd5'));
        $this->assertTrue($board->play('b', 'Kb2'));
        $this->assertTrue($board->play('w', 'Ke4'));
        $this->assertTrue($board->play('b', 'Rxa6'));

        $expected = '8/8/R7/8/4Q3/rk3K2/8/8 w - -';

        $startFen = $board->getStartFen();

        $this->assertSame($expected, $startFen);
    }

    /**
     * @test
     */
    public function endgame_checkmate_play_undo_and_get_start_fen()
    {
        $board = (new StrToBoard('8/8/R7/8/4Q3/rk3K2/8/8 w - -'))
            ->create();

        $this->assertTrue($board->play('w', 'Qd5'));
        $this->assertTrue($board->play('b', 'Kb2'));
        $this->assertTrue($board->play('w', 'Ke4'));
        $this->assertTrue($board->play('b', 'Rxa6'));

        $board = $board->undo();

        $expected = '8/8/R7/8/4Q3/rk3K2/8/8 w - -';

        $startFen = $board->getStartFen();

        $this->assertSame($expected, $startFen);
    }

    /**
     * @test
     */
    public function endgame_checkmate_play_undo_and_get_fen()
    {
        $board = (new StrToBoard('8/8/R7/8/4Q3/rk3K2/8/8 w - -'))
            ->create();

        $this->assertTrue($board->play('w', 'Qd5'));
        $this->assertTrue($board->play('b', 'Kb2'));
        $this->assertTrue($board->play('w', 'Ke4'));
        $this->assertTrue($board->play('b', 'Rxa6'));

        $board = $board->undo();

        $expected = '8/8/R7/3Q4/4K3/r7/1k6/8 b - -';

        $startFen = $board->toFen();

        $this->assertSame($expected, $startFen);
    }

    /**
     * @test
     */
    public function disambiguate_f8_rook_capture()
    {
        $board = (new StrToBoard('r1Q2rk1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 b - -'))
            ->create();

        $board->playLan('b', 'f8c8');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' . ', ' r ', ' . ', ' . ', ' . ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' p ', ' p ', ' . ' ],
            5 => [ ' . ', ' p ', ' p ', ' q ', ' . ', ' n ', ' . ', ' p ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' P ', ' P ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' . ', ' B ', ' . ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' . ', ' . ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $array);
        $this->assertSame('1...Rfxc8', $board->getMovetext());
    }

    /**
     * @test
     */
    public function disambiguate_a8_rook_capture()
    {
        $board = (new StrToBoard('r1Q2rk1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 b - -'))
            ->create();

        $board->playLan('b', 'a8c8');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' . ', ' . ', ' r ', ' . ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' p ', ' p ', ' . ' ],
            5 => [ ' . ', ' p ', ' p ', ' q ', ' . ', ' n ', ' . ', ' p ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' P ', ' P ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' . ', ' B ', ' . ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' . ', ' . ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function disambiguate_f8_rook_legal()
    {
        $board = (new StrToBoard('r1Q2rk1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 b - -'))
            ->create();

        $expected = (object) [
            'color' => 'b',
            'id' => 'R',
            'fen' => [
                'e8' => 'r1Q1r1k1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 w - -',
                'd8' => 'r1Qr2k1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 w - -',
                'c8' => 'r1r3k1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 w - -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('f8'));
    }

    /**
     * @test
     */
    public function disambiguate_c8_knight_legal()
    {
        $board = (new StrToBoard('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1'))
            ->create();

        $expected = (object) [
            'color' => 'w',
            'id' => 'N',
            'fen' => [
                'a7' => 'k5N1/N7/2K5/8/8/8/8/8 b - -',
                'b6' => 'k5N1/8/1NK5/8/8/8/8/8 b - -',
                'd6' => 'k5N1/8/2KN4/8/8/8/8/8 b - -',
                'e7' => 'k5N1/4N3/2K5/8/8/8/8/8 b - -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('c8'));
    }

    /**
     * @test
     */
    public function disambiguate_g8_knight_legal()
    {
        $board = (new StrToBoard('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1'))
            ->create();

        $expected = (object) [
            'color' => 'w',
            'id' => 'N',
            'fen' => [
                'e7' => 'k1N5/4N3/2K5/8/8/8/8/8 b - -',
                'f6' => 'k1N5/8/2K2N2/8/8/8/8/8 b - -',
                'h6' => 'k1N5/8/2K4N/8/8/8/8/8 b - -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('g8'));
    }

    /**
     * @test
     */
    public function disambiguate_c8_knight_playLan()
    {
        $board = (new StrToBoard('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1'))
            ->create();

        $board->playLan('w', 'c8e7');

        $expected = '1.Nce7';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function disambiguate_g8_knight_playLan()
    {
        $board = (new StrToBoard('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1'))
            ->create();

        $board->playLan('w', 'g8e7');

        $expected = '1.Nge7';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function disambiguate_c8_knight_capture()
    {
        $board = (new StrToBoard('k1N3N1/4p3/2K5/8/8/8/8/8 w - - 0 1'))
            ->create();

        $board->playLan('w', 'c8e7');

        $expected = '1.Ncxe7';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function disambiguate_g8_knight_capture()
    {
        $board = (new StrToBoard('k1N3N1/4p3/2K5/8/8/8/8/8 w - - 0 1'))
            ->create();

        $board->playLan('w', 'g8e7');

        $expected = '1.Ngxe7';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function disambiguate_e1_rook_capture()
    {
        $board = (new StrToBoard('4R3/k7/3K4/8/4q3/8/8/4R3 w - - 0 1'))
            ->create();

        $board->playLan('w', 'e1e4');

        $expected = '1.R1xe4';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function disambiguate_e8_rook_capture()
    {
        $board = (new StrToBoard('4R3/k7/3K4/8/4q3/8/8/4R3 w - - 0 1'))
            ->create();

        $board->playLan('w', 'e8e4');

        $expected = '1.R8xe4';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_E61()
    {
        $board = (new StrToBoard('rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'))
            ->create();

        $board->playLan('b', 'f8g7');
        $board->playLan('w', 'e2e4');

        $expected = '1...Bg7 2.e4';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O()
    {
        $board = (new StrToBoard('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'))
            ->create();

        $board->play('b', 'O-O');

        $expected = '1...O-O';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O_Nf3()
    {
        $board = (new StrToBoard('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'))
            ->create();

        $board->play('b', 'O-O');
        $board->play('w', 'Nf3');

        $expected = '1...O-O 2.Nf3';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O_Nf3_d5()
    {
        $board = (new StrToBoard('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'))
            ->create();

        $board->play('b', 'O-O');
        $board->play('w', 'Nf3');
        $board->play('b', 'd5');

        $expected = '1...O-O 2.Nf3 d5';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O_Nf3_d5_cxd5()
    {
        $board = (new StrToBoard('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'))
            ->create();

        $board->play('b', 'O-O');
        $board->play('w', 'Nf3');
        $board->play('b', 'd5');
        $board->play('w', 'cxd5');

        $expected = '1...O-O 2.Nf3 d5 3.cxd5';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_A00_c6()
    {
        $board = (new StrToBoard('rnbqkbnr/ppp2ppp/8/3pp3/5P2/6PN/PPPPP2P/RNBQKB1R b KQkq f3'))
            ->create();

        $board->play('b', 'c6');

        $expected = '1...c6';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_Qd1()
    {
        $board = (new StrToBoard('R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3BQPBP/6K1 w - -'))
            ->create();

        $board->play('w', 'Qd1');

        $expected = 'R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3B1PBP/3Q2K1 b - -';

        $this->assertEquals($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function resume_e2d1()
    {
        $board = (new StrToBoard('R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3BQPBP/6K1 w - -'))
            ->create();

        $board->playLan('w', 'e2d1');

        $expected = 'R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3B1PBP/3Q2K1 b - -';

        $this->assertEquals($expected, $board->toFen());
    }
}
