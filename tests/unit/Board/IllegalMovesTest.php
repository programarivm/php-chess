<?php

namespace PGNChess\Tests\Unit\Board;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Type\RookType;
use PHPUnit\Framework\TestCase;

class IllegalMovesTest extends TestCase
{
    /**
     * @test
     */
    public function normal_turn()
    {
        $board = new Board;

        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
    }

    /**
     * @test
     */
    public function wrong_turn()
    {
        $board = new Board;

        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals($board->getTurn(), Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));
        $this->assertEquals($board->getTurn(), Symbol::WHITE);
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Nc6')));
    }

    /**
     * @test
     */
    public function Qg5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Qg5')));
    }

    /**
     * @test
     */
    public function Ra6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra6')));
    }

    /**
     * @test
     */
    public function Rxa6()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Rxa6')));
    }

    /**
     * @test
     */
    public function Bxe5()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Bxe5')));
    }

    /**
     * @test
     */
    public function exd4()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'exd4')));
    }

    /**
     * @test
     */
    public function Nxd2()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Nxd2')));
    }

    /**
     * @test
     */
    public function Nxc3()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Nxc3')));
    }

    /**
     * @test
     */
    public function white_O_O()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function white_O_O_O()
    {
        $board = new Board;
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    /**
     * @test
     */
    public function black_O_O()
    {
        $board = new Board;
        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
    }

    /**
     * @test
     */
    public function Kf4()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'g3'),
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kf4')));
    }

    /**
     * @test
     */
    public function Kf4_check()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kf4')));
    }

    /**
     * @test
     */
    public function Kf2_check()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kf2')));
    }

    /**
     * @test
     */
    public function Re7_check()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Re7')));
    }

    /**
     * @test
     */
    public function a4_check()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'a4')));
    }

    /**
     * @test
     */
    public function Kxf2()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'g3'),
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f2', RookType::CASTLING_SHORT), // rook defended by knight
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Kxf2')));
    }

    /**
     * @test
     */
    public function white_O_O_after_Nc6()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function white_O_O_O_after_Nf6()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_f1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd4'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::BLACK, 'a6'), // bishop threatening f1
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_f1_g1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::BLACK, 'a6'), // bishop threatening f1
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'c5'), // bishop threatening g1
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_g1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'c5'), // bishop threatening g1
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_c1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f4'), // bishop threatening c1
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_d1_f1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'e3'), // knight threatening d1 and f1
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_b1_f1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'd2'), // knight threatening b1 and f1
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    /**
     * @test
     */
    public function castling_threatening_b1_d1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'c3'), // knight threatening b1 and d1
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
    }

    /**
     * @test
     */
    public function O_O_after_Kf1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kf1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ke1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nd7')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function O_O_after_Rg1()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'd5'),
            new Pawn(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::WHITE, 'f3'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rg1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rh1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nd7')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function O_O_RuyLopez()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'e5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Ke2'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Bb4'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Ke1'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Ke7'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nc3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Ke8'));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
    }

    /**
     * @test
     */
    public function opponent_threatening_castling_squares()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'c2'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Pawn(Symbol::WHITE, 'd4'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Knight(Symbol::WHITE, 'g1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Bishop(Symbol::WHITE, 'a3'),
            new Bishop(Symbol::WHITE, 'd3'),
            new Pawn(Symbol::BLACK, 'a7'),
            new Pawn(Symbol::BLACK, 'b6'),
            new Pawn(Symbol::BLACK, 'c7'),
            new Pawn(Symbol::BLACK, 'e6'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h6'),
            new Rook(Symbol::BLACK, 'a8', RookType::CASTLING_LONG),
            new Bishop(Symbol::BLACK, 'c8'),
            new Queen(Symbol::BLACK, 'd8'),
            new King(Symbol::BLACK, 'e8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Knight(Symbol::BLACK, 'd7'),
            new Knight(Symbol::BLACK, 'f6')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
    }

    /**
     * @test
     */
    public function falsly_game()
    {
        $board = new Board;

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nc6')));

        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra2')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra3')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra4')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra5')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra6')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra7')));
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::WHITE, 'Ra8')));
    }
}
