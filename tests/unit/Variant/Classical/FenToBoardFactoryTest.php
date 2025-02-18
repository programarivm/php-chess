<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        FenToBoardFactory::create('foo');
    }

    /**
     * @test
     */
    public function foo_bar_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        FenToBoardFactory::create('foo bar');
    }

    /**
     * @test
     */
    public function no_kings_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = FenToBoardFactory::create('8/8/5r1R/8/7p/8/8/8 b - - 0 1');
    }

    /**
     * @test
     */
    public function eT_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = FenToBoardFactory::create('7K/8/5e1T/8/7k/8/8/8 b - - 0 1');
    }

    /**
     * @test
     */
    public function x_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = FenToBoardFactory::create('7x/8/5k1K/8/7p/8/8/8 b - - 0 1');
    }

    /**
     * @test
     */
    public function y_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = FenToBoardFactory::create('7y/8/5k1K/8/7p/8/8/8 b - - 0 1');
    }

    /**
     * @test
     */
    public function nine_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $board = FenToBoardFactory::create('7k/9/8/8/8/8/2K5/r7 w - - 0 1');
    }

    /**
     * @test
     */
    public function null_string()
    {
        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create();

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function e4()
    {
        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function e4_e5()
    {
        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function e4_e5_flip()
    {
        $expected = [
            7 => ['R', 'N', 'B', 'K', 'Q', 'B', 'N', 'R'],
            6 => ['P', 'P', 'P', '.', 'P', 'P', 'P', 'P'],
            5 => ['.', '.', '.', '.', '.', '.', '.', '.'],
            4 => ['.', '.', '.', 'P', '.', '.', '.', '.'],
            3 => ['.', '.', '.', 'p', '.', '.', '.', '.'],
            2 => ['.', '.', '.', '.', '.', '.', '.', '.'],
            1 => ['p', 'p', 'p', '.', 'p', 'p', 'p', 'p'],
            0 => ['r', 'n', 'b', 'k', 'q', 'b', 'n', 'r'],
        ];

        $board = FenToBoardFactory::create('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2');

        $this->assertSame($expected, $board->toArray(true));
    }

    /**
     * @test
     */
    public function A59()
    {
        $expected = [
            7 => [ 'r', 'n', '.', 'q', 'k', 'b', '.', 'r' ],
            6 => [ '.', '.', '.', '.', 'p', 'p', '.', 'p' ],
            5 => [ '.', '.', '.', 'p', '.', 'n', 'p', '.' ],
            4 => [ '.', '.', 'p', 'P', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', 'N', '.', '.', '.', 'P', '.' ],
            1 => [ 'P', 'P', '.', '.', '.', 'P', '.', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', '.', 'K', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function closed_sicilian()
    {
        $expected = [
            7 => [ 'r', '.', 'b', 'q', 'k', '.', 'n', 'r' ],
            6 => [ 'p', 'p', '.', '.', 'p', 'p', 'b', 'p' ],
            5 => [ '.', '.', 'n', 'p', '.', '.', 'p', '.' ],
            4 => [ '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', 'N', 'P', '.', '.', 'P', '.' ],
            1 => [ 'P', 'P', 'P', '.', '.', 'P', 'B', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', 'K', '.', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('r1bqk1nr/pp2ppbp/2np2p1/2p5/4P3/2NP2P1/PPP2PBP/R1BQK1NR w KQkq - 0 6');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function closed_sicilian_flip()
    {
        $expected = [
            7 => ['R', 'N', '.', 'K', 'Q', 'B', '.', 'R'],
            6 => ['P', 'B', 'P', '.', '.', 'P', 'P', 'P'],
            5 => ['.', 'P', '.', '.', 'P', 'N', '.', '.'],
            4 => ['.', '.', '.', 'P', '.', '.', '.', '.'],
            3 => ['.', '.', '.', '.', '.', 'p', '.', '.'],
            2 => ['.', 'p', '.', '.', 'p', 'n', '.', '.'],
            1 => ['p', 'b', 'p', 'p', '.', '.', 'p', 'p'],
            0 => ['r', 'n', '.', 'k', 'q', 'b', '.', 'r'],
        ];

        $board = FenToBoardFactory::create('r1bqk1nr/pp2ppbp/2np2p1/2p5/4P3/2NP2P1/PPP2PBP/R1BQK1NR w KQkq - 0 6');

        $this->assertSame($expected, $board->toArray(true));
    }

    /**
     * @test
     */
    public function e4_e5_play_Nf3_Nc6()
    {
        $expected = [
            7 => [ 'r', '.', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', 'n', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', 'N', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', '.', 'R' ],
        ];

        $board = FenToBoardFactory::create('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function e4_e5_play_Nc6()
    {
        $board = FenToBoardFactory::create('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2');

        $this->assertFalse($board->play('w', 'Nc6'));
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5()
    {
        $expectedArray = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', '.', 'p', 'p', '.', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', 'p', '.', 'P', 'p', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $expectedLegalMoves = ['e6' , 'f6'];

        $board = FenToBoardFactory::create('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w KQkq f6 0 3');

        $this->assertSame($expectedArray, $board->toArray());
        $this->assertSame($expectedLegalMoves, $board->pieceBySq('e5')->moveSqs());
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5_play_exf6()
    {
        $board = FenToBoardFactory::create('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w KQkq f6 0 3');

        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function kaufman_01()
    {
        $expected = [
            7 => [ '.', 'r', 'b', 'q', '.', 'r', 'k', '.' ],
            6 => [ 'p', '.', 'b', '.', 'n', 'p', 'p', 'p' ],
            5 => [ '.', 'p', '.', '.', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', 'B', '.', 'p', 'N', '.', '.', '.' ],
            2 => [ 'P', '.', '.', 'B', '.', '.', '.', '.' ],
            1 => [ '.', 'P', '.', '.', '.', 'P', 'P', 'P' ],
            0 => [ '.', '.', 'R', 'Q', '.', 'R', '.', 'K' ],
        ];

        $board = FenToBoardFactory::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4()
    {
        $expected = [
            7 => [ '.', 'r', 'b', 'q', '.', 'r', 'k', '.' ],
            6 => [ 'p', '.', 'b', '.', 'n', 'p', 'p', 'p' ],
            5 => [ '.', 'p', '.', '.', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', 'B', '.', 'p', 'N', '.', 'Q', '.' ],
            2 => [ 'P', '.', '.', 'B', '.', '.', '.', '.' ],
            1 => [ '.', 'P', '.', '.', '.', 'P', 'P', 'P' ],
            0 => [ '.', '.', 'R', '.', '.', 'R', '.', 'K' ],
        ];

        $board = FenToBoardFactory::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $board->play('w', 'Qg4');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4_a5()
    {
        $expected = [
            7 => [ '.', 'r', 'b', 'q', '.', 'r', 'k', '.' ],
            6 => [ '.', '.', 'b', '.', 'n', 'p', 'p', 'p' ],
            5 => [ '.', 'p', '.', '.', 'p', '.', '.', '.' ],
            4 => [ 'p', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', 'B', '.', 'p', 'N', '.', 'Q', '.' ],
            2 => [ 'P', '.', '.', 'B', '.', '.', '.', '.' ],
            1 => [ '.', 'P', '.', '.', '.', 'P', 'P', 'P' ],
            0 => [ '.', '.', 'R', '.', '.', 'R', '.', 'K' ],
        ];

        $board = FenToBoardFactory::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $board->play('w', 'Qg4');
        $board->play('b', 'a5');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4_then_get_piece()
    {
        $expected = ['a6', 'a5'];

        $board = FenToBoardFactory::create('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $board->play('w', 'Qg4');

        $this->assertSame($expected, $board->pieceBySq('a7')->moveSqs());
    }

    /**
     * @test
     */
    public function endgame_king_and_rook_vs_king_cannot_capture()
    {
        $expected = ['c5', 'b4', 'b5'];

        $board = FenToBoardFactory::create('8/5k2/8/8/2K1p3/3r4/8/8 w - - 0 1');

        $this->assertEqualsCanonicalizing($expected, $board->pieceBySq('c4')->moveSqs());
    }

    /**
     * @test
     */
    public function endgame_king_and_rook_vs_king_can_capture()
    {
        $expected = ['c5', 'b4', 'b5', 'd3'];
        
        $board = FenToBoardFactory::create('8/5k2/8/8/2K5/3rp3/8/8 w - - 0 1');
        
        $this->assertEqualsCanonicalizing($expected, $board->pieceBySq('c4')->moveSqs());
    }

    /**
     * @test
     */
    public function endgame_checkmate_king_and_rook_vs_king()
    {
        $expected = ['c3', 'b2', 'd2', 'b3', 'd3'];
        
        $board = FenToBoardFactory::create('7k/8/8/8/8/8/2K5/r7 w - - 0 1');
        
        $this->assertEqualsCanonicalizing($expected, $board->pieceBySq('c2')->moveSqs());
    }

    /**
     * @test
     */
    public function endgame_checkmate_king_and_rook_vs_king_validate()
    {
        $board = FenToBoardFactory::create('7k/8/8/8/8/8/2K5/r7 w - - 0 1');

        $this->assertTrue($board->play('w', 'Kb2'));
        $this->assertTrue($board->play('b', 'Kg7'));
        $this->assertTrue($board->play('w', 'Kxa1'));
    }

    /**
     * @test
     */
    public function endgame_checkmate_play_and_get_start_fen()
    {
        $expected = '8/8/R7/8/4Q3/rk3K2/8/8 w - -';
        
        $board = FenToBoardFactory::create('8/8/R7/8/4Q3/rk3K2/8/8 w - -');

        $this->assertTrue($board->play('w', 'Qd5'));
        $this->assertTrue($board->play('b', 'Kb2'));
        $this->assertTrue($board->play('w', 'Ke4'));
        $this->assertTrue($board->play('b', 'Rxa6'));

        $this->assertSame($expected, $board->startFen);
    }

    /**
     * @test
     */
    public function endgame_checkmate_play_undo_and_get_start_fen()
    {
        $expected = '8/8/R7/8/4Q3/rk3K2/8/8 w - -';
        
        $board = FenToBoardFactory::create('8/8/R7/8/4Q3/rk3K2/8/8 w - -');

        $this->assertTrue($board->play('w', 'Qd5'));
        $this->assertTrue($board->play('b', 'Kb2'));
        $this->assertTrue($board->play('w', 'Ke4'));
        $this->assertTrue($board->play('b', 'Rxa6'));

        $board->undo();

        $this->assertSame($expected, $board->startFen);
    }

    /**
     * @test
     */
    public function endgame_checkmate_play_undo_and_get_fen()
    {
        $expected = '8/8/R7/3Q4/4K3/r7/1k6/8 b - -';

        $board = FenToBoardFactory::create('8/8/R7/8/4Q3/rk3K2/8/8 w - -');

        $this->assertTrue($board->play('w', 'Qd5'));
        $this->assertTrue($board->play('b', 'Kb2'));
        $this->assertTrue($board->play('w', 'Ke4'));
        $this->assertTrue($board->play('b', 'Rxa6'));

        $board->undo();

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function disambiguate_f8_rook_capture()
    {
        $expected = [
            7 => [ 'r', '.', 'r', '.', '.', '.', 'k', '.' ],
            6 => [ 'p', '.', '.', 'p', '.', 'p', 'p', '.' ],
            5 => [ '.', 'p', 'p', 'q', '.', 'n', '.', 'p' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            3 => [ '.', 'P', 'P', '.', 'P', '.', '.', '.' ],
            2 => [ 'P', '.', '.', '.', '.', 'N', '.', '.' ],
            1 => [ '.', 'B', '.', '.', '.', 'P', 'P', 'P' ],
            0 => [ 'R', '.', '.', '.', '.', 'R', 'K', '.' ],
        ];

        $board = FenToBoardFactory::create('r1Q2rk1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 b - -');
        $board->playLan('b', 'f8c8');

        $this->assertSame($expected, $board->toArray());
        $this->assertSame('1...Rfxc8', $board->movetext());
    }

    /**
     * @test
     */
    public function disambiguate_a8_rook_capture()
    {
        $expected = [
            7 => [ '.', '.', 'r', '.', '.', 'r', 'k', '.' ],
            6 => [ 'p', '.', '.', 'p', '.', 'p', 'p', '.' ],
            5 => [ '.', 'p', 'p', 'q', '.', 'n', '.', 'p' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            3 => [ '.', 'P', 'P', '.', 'P', '.', '.', '.' ],
            2 => [ 'P', '.', '.', '.', '.', 'N', '.', '.' ],
            1 => [ '.', 'B', '.', '.', '.', 'P', 'P', 'P' ],
            0 => [ 'R', '.', '.', '.', '.', 'R', 'K', '.' ],
        ];

        $board = FenToBoardFactory::create('r1Q2rk1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 b - -');
        $board->playLan('b', 'a8c8');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function disambiguate_f8_rook_legal()
    {
        $expected = ['e8', 'd8', 'c8'];

        $board = FenToBoardFactory::create('r1Q2rk1/p2p1pp1/1ppq1n1p/4p3/1PP1P3/P4N2/1B3PPP/R4RK1 b - -');

        $this->assertEquals($expected, $board->legal('f8'));
    }

    /**
     * @test
     */
    public function disambiguate_c8_knight_legal()
    {
        $expected = ['a7', 'b6', 'd6', 'e7'];

        $board = FenToBoardFactory::create('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1');

        $this->assertEquals($expected, $board->legal('c8'));
    }

    /**
     * @test
     */
    public function disambiguate_g8_knight_legal()
    {
        $expected = ['e7', 'f6', 'h6'];

        $board = FenToBoardFactory::create('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1');

        $this->assertEquals($expected, $board->legal('g8'));
    }

    /**
     * @test
     */
    public function disambiguate_c8_knight_playLan()
    {
        $expected = '1.Nce7';

        $board = FenToBoardFactory::create('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1');
        $board->playLan('w', 'c8e7');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function disambiguate_g8_knight_playLan()
    {
        $expected = '1.Nge7';

        $board = FenToBoardFactory::create('k1N3N1/8/2K5/8/8/8/8/8 w - - 0 1');
        $board->playLan('w', 'g8e7');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function disambiguate_c8_knight_capture()
    {
        $expected = '1.Ncxe7';

        $board = FenToBoardFactory::create('k1N3N1/4p3/2K5/8/8/8/8/8 w - - 0 1');
        $board->playLan('w', 'c8e7');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function disambiguate_g8_knight_capture()
    {
        $expected = '1.Ngxe7';

        $board = FenToBoardFactory::create('k1N3N1/4p3/2K5/8/8/8/8/8 w - - 0 1');
        $board->playLan('w', 'g8e7');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function disambiguate_e1_rook_capture()
    {
        $expected = '1.R1xe4';

        $board = FenToBoardFactory::create('4R3/k7/3K4/8/4q3/8/8/4R3 w - - 0 1');
        $board->playLan('w', 'e1e4');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function disambiguate_e8_rook_capture()
    {
        $expected = '1.R8xe4';

        $board = FenToBoardFactory::create('4R3/k7/3K4/8/4q3/8/8/4R3 w - - 0 1');
        $board->playLan('w', 'e8e4');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_E61()
    {
        $expected = '1...Bg7 2.e4';

        $board = FenToBoardFactory::create('rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -');
        $board->playLan('b', 'f8g7');
        $board->playLan('w', 'e2e4');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O()
    {
        $expected = '1...O-O';

        $board = FenToBoardFactory::create('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -');
        $board->play('b', 'O-O');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O_Nf3()
    {
        $expected = '1...O-O 2.Nf3';

        $board = FenToBoardFactory::create('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -');
        $board->play('b', 'O-O');
        $board->play('w', 'Nf3');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O_Nf3_d5()
    {
        $expected = '1...O-O 2.Nf3 d5';

        $board = FenToBoardFactory::create('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -');
        $board->play('b', 'O-O');
        $board->play('w', 'Nf3');
        $board->play('b', 'd5');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_E40_O_O_Nf3_d5_cxd5()
    {
        $expected = '1...O-O 2.Nf3 d5 3.cxd5';

        $board = FenToBoardFactory::create('rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -');
        $board->play('b', 'O-O');
        $board->play('w', 'Nf3');
        $board->play('b', 'd5');
        $board->play('w', 'cxd5');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_A00_c6()
    {
        $expected = '1...c6';
        
        $board = FenToBoardFactory::create('rnbqkbnr/ppp2ppp/8/3pp3/5P2/6PN/PPPPP2P/RNBQKB1R b KQkq f3');
        $board->play('b', 'c6');
        
        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function resume_Qd1()
    {
        $expected = 'R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3B1PBP/3Q2K1 b - -';

        $board = FenToBoardFactory::create('R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3BQPBP/6K1 w - -');
        $board->play('w', 'Qd1');

        $this->assertEquals($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function resume_e2d1()
    {
        $expected = 'R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3B1PBP/3Q2K1 b - -';

        $board = FenToBoardFactory::create('R1bq1rk1/1p6/2pp2np/4pp2/1PPnP1pP/2NP4/3BQPBP/6K1 w - -');
        $board->playLan('w', 'e2d1');

        $this->assertEquals($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function disambiguate_d4_knight_legal()
    {
        $expected = '1.Nde2';

        $board = FenToBoardFactory::create('1rb1r1k1/1pq1bppp/p2p1n2/4p3/P1nNPP2/2N2B2/1PP2QPP/R1B2R1K w - -');
        $board->playLan('w', 'd4e2');

        $this->assertEquals($expected, $board->movetext());
    }

        /**
     * @test
     */
    public function disambiguate_c1_rook_legal()
    {
        $expected = '1.Rcc2';

        $board = FenToBoardFactory::create('r5k1/2rb2p1/3p3p/q2Pbp2/P1P1p3/1QN3PP/R4P2/2R2BK1 w - -');
        $board->playLan('w', 'c1c2');

        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function en_passant_b4_e6_b5_c5_bxc6()
    {
        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', '.', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', 'P', '.', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', '.', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('rnbqkbnr/pp1p1ppp/4p3/1Pp5/8/8/P1PPPPPP/RNBQKBNR w KQkq c6');
        $board->play('w', 'bxc6');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function en_passant_bxc6()
    {
        $expected = [
            7 => [ 'r', '.', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', '.', '.', '.', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', 'P', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', '.', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('r1bqkbnr/p3pppp/8/1Pp5/8/8/P1PPPPPP/RNBQKBNR w KQkq c6 0 1');
        $board->play('w', 'bxc6');

        $this->assertSame($expected, $board->toArray());
    }
}
