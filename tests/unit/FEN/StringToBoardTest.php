<?php

namespace Chess\Tests\Unit\FEN;

use Chess\Ascii;
use Chess\Exception\UnknownNotationException;
use Chess\FEN\StringToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Generator;

class StringToBoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4()
    {
        $board = (new StringToBoard('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1'))
            ->create();

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $array = (new Ascii())->toArray($board, true);

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

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function benko_gambit()
    {
        $board = (new StringToBoard('rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9'))
            ->create();

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('r1b1kbnr/1pp2ppp/p1p5/8/3NP3/8/PPP2PPP/RNB1K2R b KQkq - 0 7'))
            ->create();

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('r1bqk1nr/pp2ppbp/2np2p1/2p5/4P3/2NP2P1/PPP2PBP/R1BQK1NR w KQkq - 0 6'))
            ->create();

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('r1bqk1nr/pp2ppbp/2np2p1/2p5/4P3/2NP2P1/PPP2PBP/R1BQK1NR w KQkq - 0 6'))
            ->create();

        $array = (new Ascii())->toArray($board,true);

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

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_play_Nf3_Nc6()
    {
        $board = (new StringToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2'))
            ->create();

        $this->assertFalse($board->play('w', 'Nc6'));
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5()
    {
        $board = (new StringToBoard('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w KQkq f6 0 3'))
            ->create();

        $array = (new Ascii())->toArray($board);

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

        $legalMoves = $board->getPieceByPosition('e5')->getLegalMoves();

        $this->assertSame($expected, $array);
        $this->assertSame($legalMoves, ['e6' , 'f6']);
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5_play_exf6()
    {
        $board = (new StringToBoard('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w KQkq f6 0 3'))
            ->create();

        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function kaufman_01()
    {
        $board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $board->play('w', 'Qg4');

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $board->play('w', 'Qg4');
        $board->play('b', 'a5');

        $array = (new Ascii())->toArray($board);

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
        $board = (new StringToBoard('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+'))
            ->create();

        $board->play('w', 'Qg4');

        $legalMoves = $board->getPieceByPosition('a7')->getLegalMoves();

        $expected = ['a6', 'a5'];

        $this->assertSame($expected, $legalMoves);
    }

    /**
     * @test
     * @dataProvider provideInvalidFen
     */
    public function it_throws_for_invalid_fen(string $invalidFen): void
    {
        $this->expectException(UnknownNotationException::class);
        $this->expectExceptionMessage('The FEN string should contain a valid piece placement.');

        (new StringToBoard($invalidFen))
            ->create();
    }

    public function provideInvalidFen(): Generator
    {
        yield ['foo'];
        yield ['foo bar dont panic'];
    }
}
