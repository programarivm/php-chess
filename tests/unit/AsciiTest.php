<?php

namespace Chess\Tests\Unit;

use Chess\Ascii;
use Chess\Board;
use Chess\Castling\Rule as CastlingRule;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\RuyLopez\Exchange as RuyLopezExchange;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\Benoni\FianchettoVariation as BenoniFianchettoVariation;
use Chess\Tests\Sample\Opening\FrenchDefense\Classical as FrenchDefenseClassical;
use Chess\Tests\Sample\Opening\FrenchDefense\Exchange as FrenchDefenseExchange;
use Chess\Tests\Sample\Opening\QueensGambit\SymmetricalDefense as QueensGambitSymmetricalDefense;

class AsciiTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5_to_array()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));

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

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_to_board()
    {
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true,
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true,
            ],
        ];

        $board = (new Ascii())->toBoard($expected, Symbol::WHITE, $castling);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function benko_gambit_to_array()
    {
        $board = (new BenkoGambit(new Board()))->play();

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

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function benko_gambit_to_board()
    {
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false,
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true,
            ],
        ];

        $board = (new Ascii())->toBoard($expected, Symbol::BLACK, $castling);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function benoni_fianchetto_variation_to_array()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' r ', ' . ', ' k ', ' . ' ],
            6 => [ ' . ', ' p ', ' . ', ' n ', ' . ', ' p ', ' b ', ' p ' ],
            5 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' N ', ' P ', ' P ', ' B ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function benoni_fianchetto_variation_to_board()
    {
        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' r ', ' . ', ' k ', ' . ' ],
            6 => [ ' . ', ' p ', ' . ', ' n ', ' . ', ' p ', ' b ', ' p ' ],
            5 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' N ', ' P ', ' P ', ' B ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false,
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false,
            ],
        ];

        $board = (new Ascii())->toBoard($expected, Symbol::BLACK, $castling);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function french_defense_classical_to_array()
    {
        $board = (new FrenchDefenseClassical(new Board()))->play();

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' n ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' P ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function french_defense_exchange_to_array()
    {
        $board = (new FrenchDefenseExchange(new Board()))->play();

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function french_defense_classical_to_board()
    {
        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' n ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' P ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $board = (new Ascii())->toBoard($expected, Symbol::WHITE);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function symetrical_defence_to_queen_gambit()
    {
        $board = (new QueensGambitSymmetricalDefense())->play();

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' P ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function symetrical_defence_to_queen_gambit_to_board()
    {
        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' P ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $board = (new Ascii())->toBoard($expected, Symbol::WHITE);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange_to_array()
    {
        $board = (new RuyLopezExchange(new Board()))->play();

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

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange_to_board()
    {
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

        $board = (new Ascii())->toBoard($expected, Symbol::BLACK);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange_undo_move_to_array()
    {
        $board = (new RuyLopezExchange(new Board()))->play();
        $board = $board->undoMove($board->getCastling());

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' . ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' . ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' p ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' q ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' . ', ' K ', ' . ', ' . ', ' R ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange_undo_two_moves_to_array()
    {
        $board = (new RuyLopezExchange(new Board()))->play();
        $board = $board->undoMove($board->getCastling());
        $board = $board->undoMove($board->getCastling());

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' . ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' p ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' Q ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' . ', ' K ', ' . ', ' . ', ' R ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function closed_sicilian_to_array()
    {
        $board = (new ClosedSicilian(new Board()))->play();

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

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function closed_sicilian_to_board()
    {
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

        $board = (new Ascii())->toBoard($expected, Symbol::WHITE);
        $array = (new Ascii())->toArray($board);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_0_0()
    {
        $this->assertEquals('a1', (new Ascii())->fromIndexToAlgebraic(0, 0));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_1_0()
    {
        $this->assertEquals('a2', (new Ascii())->fromIndexToAlgebraic(1, 0));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_6_7()
    {
        $this->assertEquals('h7', (new Ascii())->fromIndexToAlgebraic(6, 7));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_7_7()
    {
        $this->assertEquals('h8', (new Ascii())->fromIndexToAlgebraic(7, 7));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_a1()
    {
        $this->assertEquals([0, 0], (new Ascii())->fromAlgebraicToIndex('a1'));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_a2()
    {
        $this->assertEquals([1, 0], (new Ascii())->fromAlgebraicToIndex('a2'));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_h7()
    {
        $this->assertEquals([6, 7], (new Ascii())->fromAlgebraicToIndex('h7'));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_h8()
    {
        $this->assertEquals([7, 7], (new Ascii())->fromAlgebraicToIndex('h8'));
    }

    /**
     * @test
     */
    public function e4_e5_set_array_elem()
    {
        $array = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' . ', ' R ' ],
        ];

        (new Ascii())
            ->setArrayElem(' . ', 'g1', $array)
            ->setArrayElem(' N ', 'f3', $array);

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Ba4_b5_Bb3_Bb7_a4_Nf6_Nc3_g6_Qe2_d6_d3_Be7_Bg5_Qd7_O_O_O_O_O()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Bb5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'a6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Ba4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'b5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Bb3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bb7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'a4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nc3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'g6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Qe2')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'd6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Be7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Bg5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Qd7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'O-O')));

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' . ', ' . ', ' . ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' . ', ' b ', ' p ', ' q ', ' b ', ' p ', ' . ', ' p ' ],
            5 => [ ' p ', ' . ', ' n ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' p ', ' . ', ' . ', ' p ', ' . ', ' B ', ' . ' ],
            3 => [ ' P ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' B ', ' N ', ' P ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' P ', ' . ', ' Q ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' K ', ' R ', ' . ', ' . ', ' . ', ' R ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8_Bg7_d3_Bxh8_Qe2_Nge7_c3()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Bc4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'h6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'h4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'g5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'hxg5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'hxg5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Rxh8')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bg7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bxh8')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Qe2')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nge7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'c3')));

        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' . ', ' . ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' n ', ' p ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' n ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' p ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' P ', ' P ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' . ', ' Q ', ' P ', ' P ', ' . ' ],
            0 => [ ' R ', ' N ', ' B ', ' . ', ' K ', ' . ', ' . ', ' . ' ],
        ];

        $this->assertEquals($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8_Bg7_d3_Bxh8_Qe2_Nge7_c3_g4()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Bc4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'h6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'h4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'g5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'hxg5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'hxg5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Rxh8')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bg7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bxh8')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Qe2')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nge7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'c3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'g4')));


        $array = (new Ascii())->toArray($board);

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' . ', ' . ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' n ', ' p ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' n ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' p ', ' . ' ],
            2 => [ ' . ', ' . ', ' P ', ' P ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' . ', ' Q ', ' P ', ' P ', ' . ' ],
            0 => [ ' R ', ' N ', ' B ', ' . ', ' K ', ' . ', ' . ', ' . ' ],
        ];

        $this->assertEquals($expected, $array);
    }
}
