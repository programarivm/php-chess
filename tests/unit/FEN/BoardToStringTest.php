<?php

namespace Chess\Tests\Unit\FEN;

use Chess\Board;
use Chess\FEN\BoardToString;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\RuyLopez\Exchange as RuyLopezExchange;

class BoardToStringTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));

        $boardToString = (new BoardToString($board))->create();

        $expected = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3';

        $this->assertEquals($expected, $boardToString);
    }

    /**
     * @test
     */
    public function e4_e5()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));

        $boardToString = (new BoardToString($board))->create();

        $expected = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6';

        $this->assertEquals($expected, $boardToString);
    }

    /**
     * @test
     */
    public function benko_gambit()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $boardToString = (new BoardToString($board))->create();

        $expected = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq -';

        $this->assertEquals($expected, $boardToString);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange()
    {
        $board = (new RuyLopezExchange(new Board()))->play();

        $boardToString = (new BoardToString($board))->create();

        $expected = 'r1b1kbnr/1pp2ppp/p1p5/8/3NP3/8/PPP2PPP/RNB1K2R b KQkq -';

        $this->assertEquals($expected, $boardToString);
    }

    /**
     * @test
     */
    public function castling_short()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Be2'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Be7'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'O-O'));

        $boardToString = (new BoardToString($board))->create();

        $expected = 'r1bqk1nr/ppppbppp/2n5/4p3/4P3/5N2/PPPPBPPP/RNBQ1RK1 b kq -';

        $this->assertEquals($expected, $boardToString);
    }

    /**
     * @test
     *
     * TODO: The castling data needs to be refactored.
     *
     * Expected value:
     *  'r1bqkbnR/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 b Qq -'
     *
     * Actual value:
     *  'r1bqkbnR/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 b Qkq -'
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Bc4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'h6'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'h4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'g5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'hxg5'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'hxg5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Rxh8'));

        $boardToString = (new BoardToString($board))->create();

        $expected = 'r1bqkbnR/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 b Qkq -';

        $this->assertEquals($expected, $boardToString);
    }
}
