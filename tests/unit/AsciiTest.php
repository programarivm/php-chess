<?php

namespace Chess\Tests\Unit;

use Chess\Ascii;
use Chess\Board;
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
        $board->play('w', 'e4');
        $board->play('b', 'e5');

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
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true,
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true,
            ],
        ];

        $board = (new Ascii())->toBoard($expected, 'w', $castling);
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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
            'w' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false,
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true,
            ],
        ];

        $board = (new Ascii())->toBoard($expected, 'b', $castling);
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false,
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false,
            ],
        ];

        $board = (new Ascii())->toBoard($expected, 'b', $castling);
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $board = (new Ascii())->toBoard($expected, 'w');
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $board = (new Ascii())->toBoard($expected, 'w');
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $board = (new Ascii())->toBoard($expected, 'b');
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $this->assertSame($expected, $array);
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

        $board = (new Ascii())->toBoard($expected, 'w');
        $array = (new Ascii())->toArray($board);

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_0_0()
    {
        $this->assertSame('a1', (new Ascii())->fromIndexToAlgebraic(0, 0));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_1_0()
    {
        $this->assertSame('a2', (new Ascii())->fromIndexToAlgebraic(1, 0));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_6_7()
    {
        $this->assertSame('h7', (new Ascii())->fromIndexToAlgebraic(6, 7));
    }

    /**
     * @test
     */
    public function from_index_to_algebraic_7_7()
    {
        $this->assertSame('h8', (new Ascii())->fromIndexToAlgebraic(7, 7));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_a1()
    {
        $this->assertSame([0, 0], (new Ascii())->fromAlgebraicToIndex('a1'));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_a2()
    {
        $this->assertSame([1, 0], (new Ascii())->fromAlgebraicToIndex('a2'));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_h7()
    {
        $this->assertSame([6, 7], (new Ascii())->fromAlgebraicToIndex('h7'));
    }

    /**
     * @test
     */
    public function from_algebraic_to_index_h8()
    {
        $this->assertSame([7, 7], (new Ascii())->fromAlgebraicToIndex('h8'));
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

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Ba4_b5_Bb3_Bb7_a4_Nf6_Nc3_g6_Qe2_d6_d3_Be7_Bg5_Qd7_O_O_O_O_O()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nc6'));
        $this->assertTrue($board->play('w', 'Bb5'));
        $this->assertTrue($board->play('b', 'a6'));
        $this->assertTrue($board->play('w', 'Ba4'));
        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'Bb3'));
        $this->assertTrue($board->play('b', 'Bb7'));
        $this->assertTrue($board->play('w', 'a4'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Nc3'));
        $this->assertTrue($board->play('b', 'g6'));
        $this->assertTrue($board->play('w', 'Qe2'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'Be7'));
        $this->assertTrue($board->play('w', 'Bg5'));
        $this->assertTrue($board->play('b', 'Qd7'));
        $this->assertTrue($board->play('w', 'O-O-O'));
        $this->assertTrue($board->play('b', 'O-O'));

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

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8_Bg7_d3_Bxh8_Qe2_Nge7_c3()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nc6'));
        $this->assertTrue($board->play('w', 'Bc4'));
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertTrue($board->play('w', 'h4'));
        $this->assertTrue($board->play('b', 'g5'));
        $this->assertTrue($board->play('w', 'hxg5'));
        $this->assertTrue($board->play('b', 'hxg5'));
        $this->assertTrue($board->play('w', 'Rxh8'));
        $this->assertTrue($board->play('b', 'Bg7'));
        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'Bxh8'));
        $this->assertTrue($board->play('w', 'Qe2'));
        $this->assertTrue($board->play('b', 'Nge7'));
        $this->assertTrue($board->play('w', 'c3'));

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

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8_Bg7_d3_Bxh8_Qe2_Nge7_c3_g4()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nc6'));
        $this->assertTrue($board->play('w', 'Bc4'));
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertTrue($board->play('w', 'h4'));
        $this->assertTrue($board->play('b', 'g5'));
        $this->assertTrue($board->play('w', 'hxg5'));
        $this->assertTrue($board->play('b', 'hxg5'));
        $this->assertTrue($board->play('w', 'Rxh8'));
        $this->assertTrue($board->play('b', 'Bg7'));
        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'Bxh8'));
        $this->assertTrue($board->play('w', 'Qe2'));
        $this->assertTrue($board->play('b', 'Nge7'));
        $this->assertTrue($board->play('w', 'c3'));
        $this->assertTrue($board->play('b', 'g4'));


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

        $this->assertSame($expected, $array);
    }
}
