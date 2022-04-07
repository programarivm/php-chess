<?php

namespace Chess\Tests\Unit;

use Chess\Board;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\PGN\Move;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\RuyLopez\Exchange as ExchangeRuyLopez;
use Chess\Tests\Sample\Opening\RuyLopez\LucenaDefense as LucenaDefense;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;
use Chess\Tests\Sample\Opening\Benoni\FianchettoVariation as BenoniFianchettoVariation;
use Chess\Tests\Sample\Opening\QueensGambit\SymmetricalDefense as SymmetricalDefense;
use Chess\Tests\Sample\Opening\FrenchDefense\Classical as ClassicalFrenchDefense;

class BoardTest extends AbstractUnitTestCase
{
    /*
    |--------------------------------------------------------------------------
    | play()
    |--------------------------------------------------------------------------
    |
    | Invalid moves throw an exception.
    |
    */

    /**
     * @test
     */
    public function play_w_9()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 9);
    }

    /**
     * @test
     */
    public function play_w_foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'foo');
    }

    /**
     * @test
     */
    public function play_w_e9()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'e9');
    }

    /**
     * @test
     */
    public function play_w_Nw3()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'Nw3');
    }

    /**
     * @test
     */
    public function init_board_and_pick_a_nonexistent_piece()
    {
        $this->expectException(\Chess\Exception\BoardException::class);

        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        (new Board($pieces, $castle))->play('w', 'f4');
    }

    /*
    |--------------------------------------------------------------------------
    | play()
    |--------------------------------------------------------------------------
    |
    | Illegal moves return false.
    |
    */

    /**
     * @test
     */
    public function play_b_Qg5()
    {
        $board = new Board();

        $this->assertFalse($board->play('b', 'Qg5'));
    }

    /**
     * @test
     */
    public function play_w_Ra6()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function play_b_Rxa6()
    {
        $board = new Board();

        $this->assertFalse($board->play('b', 'Rxa6'));
    }

    /**
     * @test
     */
    public function play_w_Bxe5()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'Bxe5'));
    }

    /**
     * @test
     */
    public function play_w_exd4()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'exd4'));
    }

    /**
     * @test
     */
    public function play_w_Nxd2()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'Nxd2'));
    }

    /**
     * @test
     */
    public function play_w_O_O()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function play_b_O_O()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $this->assertFalse($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function play_a_falsy_game()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
        $this->assertFalse($board->play('w', 'e5'));

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nc6'));

        $this->assertFalse($board->play('w', 'Ra2'));
        $this->assertFalse($board->play('w', 'Ra3'));
        $this->assertFalse($board->play('w', 'Ra4'));
        $this->assertFalse($board->play('w', 'Ra5'));
        $this->assertFalse($board->play('w', 'Ra6'));
        $this->assertFalse($board->play('w', 'Ra7'));
        $this->assertFalse($board->play('w', 'Ra8'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf4()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf4_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf2_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'Kf2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Re7_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'Re7'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_a4_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'a4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxf2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f2', RookType::O_O), // rook defended by knight
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'Kxf2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'c5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'c5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'Nf6');

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_with_threats_on_f1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd4'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Bishop('b', 'a6'), // bishop threatening f1
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_with_threats_on_f1_g1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Bishop('b', 'a6'), // bishop threatening f1
            new King('b', 'e8'),
            new Bishop('b', 'c5'), // bishop threatening g1
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_with_threats_on_g1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'c5'), // bishop threatening g1
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_O_with_threats_on_c1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'f4'), // bishop threatening c1
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_castle_with_threats_on_d1_f1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'e3'), // knight threatening d1 and f1
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_castle_with_threats_on_b1_f1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'd2'), // knight threatening b1 and f1
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_O_with_threats_on_b1_d1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'c3'), // knight threatening b1 and d1
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_after_Kf1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Kf1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Ke1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_O_O_after_Rg1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Rg1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Rh1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_O_O_with_threats()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'c2'),
            new Pawn('w', 'c3'),
            new Pawn('w', 'd4'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Knight('w', 'g1'),
            new Rook('w', 'h1', RookType::O_O),
            new Bishop('w', 'a3'),
            new Bishop('w', 'd3'),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b6'),
            new Pawn('b', 'c7'),
            new Pawn('b', 'e6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h6'),
            new Rook('b', 'a8', RookType::O_O_O),
            new Bishop('b', 'c8'),
            new Queen('b', 'd8'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::O_O),
            new Knight('b', 'd7'),
            new Knight('b', 'f6')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertFalse($board->play('b', 'O-O'));
    }

    /*
    |--------------------------------------------------------------------------
    | play()
    |--------------------------------------------------------------------------
    |
    | Legal moves return true.
    |
    */

    /**
     * @test
     */
    public function init_board_and_play_w_Ra6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::O_O_O),
            new Queen('w', 'd1'),
            new King('w', 'e1'),
            new Bishop('w', 'f1'),
            new Knight('w', 'g1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('w', 'b2'),
            new Pawn('w', 'c2'),
            new Pawn('w', 'd2'),
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('b', 'a8', RookType::O_O_O),
            new Knight('b', 'b8'),
            new Bishop('b', 'c8'),
            new Queen('b', 'd8'),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b7'),
            new Pawn('b', 'c7'),
            new Pawn('b', 'd7'),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Rxa6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Rxa6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_h6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'h6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_hxg6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::O_O_O),
            new King('w', 'e1'),
            new Pawn('w', 'g6'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'hxg6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Nxc3()
    {
        $pieces = [
            new Knight('w', 'b1'),
            new King('w', 'e1'),
            new Pawn('w', 'g6'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'c3'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Nxc3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_O_O()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::O_O_O),
            new Knight('w', 'b1'),
            new Bishop('w', 'c1'),
            new Queen('w', 'd1'),
            new King('w', 'e1'),
            new Bishop('w', 'f1'),
            new Knight('w', 'g1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('w', 'a2'),
            new Pawn('w', 'b2'),
            new Pawn('w', 'c2'),
            new Pawn('w', 'd2'),
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('b', 'a8', RookType::O_O_O),
            new Knight('b', 'b8'),
            new Bishop('b', 'c8'),
            new Queen('b', 'd8'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::O_O),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b7'),
            new Pawn('b', 'c7'),
            new Pawn('b', 'd7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true
            ]
        ];

        $board = new Board($pieces, $castle);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke4_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Ke4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg3_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Kg3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg2_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke2_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Ke2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke3_in_check()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Ke3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxh2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'h2', RookType::O_O),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Kxh2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxf3()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::O_O_O),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f3', RookType::O_O), // rook not defended
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'Kxf3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_exf3()
    {
        $pieces = [
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('b', 'e4'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::O_O)
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'f4'));
        $this->assertTrue($board->play('b', 'exf3')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_w_exf6()
    {
        $pieces = [
            new Pawn('w', 'e5'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::O_O)
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'f5'));
        $this->assertTrue($board->play('w', 'exf6')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_b_gxh3()
    {
        $pieces = [
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g4'),
            new Pawn('b', 'h7'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::O_O)
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'h4'));
        $this->assertTrue($board->play('b', 'gxh3')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_b_hxg3()
    {
        $pieces = [
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h4'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::O_O)
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'g4'));
        $this->assertTrue($board->play('b', 'hxg3'));
    }

    /**
     * @test
     */
    public function play_a_sequence_of_moves_w_exf6()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e6'));
        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'Nc3'));
        $this->assertTrue($board->play('b', 'Bb4'));
        $this->assertTrue($board->play('w', 'e5'));
        $this->assertTrue($board->play('b', 'c5'));
        $this->assertTrue($board->play('w', 'Qg4'));
        $this->assertTrue($board->play('b', 'Ne7'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nbc6'));
        $this->assertTrue($board->play('w', 'a3'));
        $this->assertTrue($board->play('b', 'Bxc3+'));
        $this->assertTrue($board->play('w', 'bxc3'));
        $this->assertTrue($board->play('b', 'Qc7'));
        $this->assertTrue($board->play('w', 'Rb1'));
        $this->assertTrue($board->play('b', 'O-O'));
        $this->assertTrue($board->play('w', 'Bd3'));
        $this->assertTrue($board->play('b', 'f5'));
        $pawn_e5 = $board->getPieceBySq('e5');
        $pawn_e5->getSqs(); // this creates the en passant property
        $this->assertSame('f5', $pawn_e5->getEnPassantSq());
        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_cxb6()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'b2'),
            new Pawn('w', 'c5'),
            new Rook('w', 'd1', RookType::O_O_O),
            new King('w', 'e4'),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b7'),
            new Pawn('b', 'c7'),
            new King('b', 'g6'),
            new Rook('b', 'h8', RookType::O_O_O),
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'cxb6')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_promote_w_h8()
    {
        $pieces = [
            new Pawn('w', 'g2'),
            new Pawn('w', 'h7'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::O_O),
            new Pawn('b', 'c7'),
            new Pawn('b', 'd7'),
            new Pawn('b', 'e7'),
            new Bishop('b', 'd6'),
            new King('b', 'e8')
        ];

        $castle = [
            'w' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'h8=Q'));
    }

    /**
     * @test
     */
    public function init_board_and_checkmate_w_Qd7()
    {
        $pieces = [
            new Pawn('w', 'd5'),
            new Queen('w', 'f5'),
            new King('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'h8', RookType::O_O_O),
            new King('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castle);

        $this->assertTrue($board->play('w', 'd6+'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->play('b', 'Kd7'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->play('b', 'Ke6'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('b', 'Kxd6'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('w', 'Re8'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('b', 'Kc7'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('w', 'Re7+'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('b', 'Kd8'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('w', 'Qd7#'));
        $this->assertTrue($board->isCheck());
        $this->assertTrue($board->isMate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_queen_vs_king()
    {
        $pieces = [
            new King('b', 'h1'),
            new King('w', 'a8'),
            new Queen('w', 'f2'),
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = (new Board($pieces, $castle))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_pawn_vs_king()
    {
        $pieces = [
            new King('w', 'f6'),
            new Pawn('w', 'f7'),
            new King('b', 'f8'),
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = (new Board($pieces, $castle))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_rook_vs_king_and_bishop()
    {
        $pieces = [
            new King('w', 'b6'),
            new Rook('w', 'h8', RookType::O_O_O),
            new King('b', 'a8'),
            new Bishop('b', 'b8'),
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = (new Board($pieces, $castle))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_endgame()
    {
        $pieces = [
            new King('w', 'g1'),
            new Queen('w', 'd1'),
            new Rook('w', 'a5', RookType::O_O),
            new Rook('w', 'b7', RookType::O_O_O),
            new Pawn('w', 'f6'),
            new Pawn('w', 'g5'),
            new King('b', 'e6'),
        ];

        $castle = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = (new Board($pieces, $castle))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /*
    |--------------------------------------------------------------------------
    | possibleMoves()
    |--------------------------------------------------------------------------
    |
    | Possible moves.
    |
    */

    /**
     * @test
     */
    public function possible_moves_on_start()
    {
        $board = new Board();

        $expected = [
            'Na3',
            'Nc3',
            'Nf3',
            'Nh3',
            'a3',
            'a4',
            'b3',
            'b4',
            'c3',
            'c4',
            'd3',
            'd4',
            'e3',
            'e4',
            'f3',
            'f4',
            'g3',
            'g4',
            'h3',
            'h4',
        ];

        $this->assertSame($expected, $board->possibleMoves());
    }

    /**
     * @test
     */
    public function possible_moves_after_e4()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $expected = [
            'Na6',
            'Nc6',
            'Nf6',
            'Nh6',
            'a6',
            'a5',
            'b6',
            'b5',
            'c6',
            'c5',
            'd6',
            'd5',
            'e6',
            'e5',
            'f6',
            'f5',
            'g6',
            'g5',
            'h6',
            'h5',
        ];

        $this->assertSame($expected, $board->possibleMoves());
    }

    /**
     * @test_
     */
    public function possible_moves_after_e4_e5_Nf3_Nf6_Be2_Be7()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Be2');
        $board->play('b', 'Be7');

        $expected = [
            'Na3',
            'Nc3',
            'Kf1',
            'O-O',
            'Rg1',
            'Rf1',
            'a3',
            'a4',
            'b3',
            'b4',
            'c3',
            'c4',
            'd3',
            'd4',
            'g3',
            'g4',
            'h3',
            'h4',
            'Nxe5',
            'Nd4',
            'Ng1',
            'Nh4',
            'Ng5',
            'Bd3',
            'Bc4',
            'Bb5',
            'Ba6',
            'Bf1',
        ];

        $this->assertSame($expected, $board->possibleMoves());
    }

    /*
    |--------------------------------------------------------------------------
    | getCaptures()
    |--------------------------------------------------------------------------
    |
    | The captured pieces.
    |
    */

    /**
     * @test
     */
    public function captures_in_exchange_ruy_lopez()
    {
        $board = (new ExchangeRuyLopez(new Board()))->play();

        $expected = [
            'w' => [
                (object) [
                    'capturing' => (object) [
                        'id' => 'B',
                        'sq' => 'b5',
                    ],
                    'captured' => (object) [
                        'id' => 'N',
                        'sq' => 'c6',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'id' => 'Q',
                        'sq' => 'd1',
                    ],
                    'captured' => (object) [
                        'id' => 'P',
                        'sq' => 'd4',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'id' => 'N',
                        'sq' => 'f3',
                    ],
                    'captured' => (object) [
                        'id' => 'Q',
                        'sq' => 'd4',
                    ],
                ],
            ],
            'b' => [
                (object) [
                    'capturing' => (object) [
                        'id' => 'P',
                        'sq' => 'd7',
                    ],
                    'captured' => (object) [
                        'id' => 'B',
                        'sq' => 'c6',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'id' => 'P',
                        'sq' => 'e5',
                    ],
                    'captured' => (object) [
                        'id' => 'P',
                        'sq' => 'd4',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'id' => 'Q',
                        'sq' => 'd8',
                    ],
                    'captured' => (object) [
                        'id' => 'Q',
                        'sq' => 'd4',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $board->getCaptures());
    }

    /*
    |--------------------------------------------------------------------------
    | getHistory()
    |--------------------------------------------------------------------------
    |
    | The history of the game.
    |
    */

    /**
     * @test
     */
    public function history_in_symmetrical_defense()
    {
        $board = (new SymmetricalDefense(new Board()))->play();

        $expected = [
            (object) [
                'sq' => 'd2',
                'move' => (object) [
                    'pgn' => 'd4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd4',
                    ],
                ],
            ],
            (object) [
                'sq' => 'd7',
                'move' => (object) [
                    'pgn' => 'd5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd5',
                    ],
                ],
            ],
            (object) [
                'sq' => 'c2',
                'move' => (object) [
                    'pgn' => 'c4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'c',
                        'next' => 'c4',
                    ],
                ],
            ],
            (object) [
                'sq' => 'c7',
                'move' => (object) [
                    'pgn' => 'c5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'c',
                        'next' => 'c5',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $board->getHistory());
    }

    /**
     * @test
     */
    public function history_in_lucena_defense()
    {
        $board = (new LucenaDefense(new Board()))->play();

        $expected = [
            (object) [
                'sq' => 'e2',
                'move' => (object) [
                    'pgn' => 'e4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e4',
                    ],
                ],
            ],
            (object) [
                'sq' => 'e7',
                'move' => (object) [
                    'pgn' => 'e5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e5',
                    ],
                ],
            ],
            (object) [
                'sq' => 'g1',
                'move' => (object) [
                    'pgn' => 'Nf3',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'f3',
                    ],
                ],
            ],
            (object) [
                'sq' => 'b8',
                'move' => (object) [
                    'pgn' => 'Nc6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'c6',
                    ],
                ],
            ],
            (object) [
                'sq' => 'f1',
                'move' => (object) [
                    'pgn' => 'Bb5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PIECE,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::B,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'b5',
                    ],
                ],
            ],
            (object) [
                'sq' => 'f8',
                'move' => (object) [
                    'pgn' => 'Be7',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PIECE,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::B,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'e7',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $board->getHistory());
    }

    /**
     * @test
     */
    public function history_in_classical_french_defense()
    {
        $board = (new ClassicalFrenchDefense(new Board()))->play();

        $expected = [
            (object) [
                'sq' => 'e2',
                'move' => (object) [
                    'pgn' => 'e4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e4',
                    ],
                ],
            ],
            (object) [
                'sq' => 'e7',
                'move' => (object) [
                    'pgn' => 'e6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e6',
                    ],
                ],
            ],
            (object) [
                'sq' => 'd2',
                'move' => (object) [
                    'pgn' => 'd4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd4',
                    ],
                ],
            ],
            (object) [
                'sq' => 'd7',
                'move' => (object) [
                    'pgn' => 'd5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd5',
                    ],
                ],
            ],
            (object) [
                'sq' => 'b1',
                'move' => (object) [
                    'pgn' => 'Nc3',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Symbol::WHITE,
                    'id' => Symbol::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'c3',
                    ],
                ],
            ],
            (object) [
                'sq' => 'g8',
                'move' => (object) [
                    'pgn' => 'Nf6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Symbol::BLACK,
                    'id' => Symbol::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'f6',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $board->getHistory());
    }

    /*
    |--------------------------------------------------------------------------
    | getPiecesByColor()
    |--------------------------------------------------------------------------
    |
    | Gets the pieces by color.
    |
    */

    /**
     * @test
     */
    public function get_pieces_in_benko_gambit()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $this->assertSame(14, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertSame(13, count($board->getPiecesByColor(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function get_pieces_in_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $this->assertSame(15, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertSame(15, count($board->getPiecesByColor(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function get_pieces_in_open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $this->assertSame(15, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertSame(15, count($board->getPiecesByColor(Symbol::BLACK)));
    }
}
