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

class LegalMovesTest extends TestCase
{
    /**
     * @test
     */
    public function Ra6()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new Queen(Symbol::WHITE, 'd1'),
            new King(Symbol::WHITE, 'e1'),
            new Bishop(Symbol::WHITE, 'f1'),
            new Knight(Symbol::WHITE, 'g1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::WHITE, 'b2'),
            new Pawn(Symbol::WHITE, 'c2'),
            new Pawn(Symbol::WHITE, 'd2'),
            new Pawn(Symbol::WHITE, 'e2'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::BLACK, 'a8', RookType::CASTLING_LONG),
            new Knight(Symbol::BLACK, 'b8'),
            new Bishop(Symbol::BLACK, 'c8'),
            new Queen(Symbol::BLACK, 'd8'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'a7'),
            new Pawn(Symbol::BLACK, 'b7'),
            new Pawn(Symbol::BLACK, 'c7'),
            new Pawn(Symbol::BLACK, 'd7'),
            new Pawn(Symbol::BLACK, 'e7'),
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
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ra6')));
    }

    /**
     * @test
     */
    public function Rxa6()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rxa6')));
    }

    /**
     * @test
     */
    public function h6()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'h6')));
    }

    /**
     * @test
     */
    public function hxg6()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e1'),
            new Pawn(Symbol::WHITE, 'g6'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'hxg6')));
    }

    /**
     * @test
     */
    public function Nc3()
    {
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nc3')));
    }

    /**
     * @test
     */
    public function Nc6()
    {
        $board = new Board;
        $board->setTurn(Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nc6')));
    }

    /**
     * @test
     */
    public function Nf6()
    {
        $board = new Board;
        $board->setTurn(Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nf6')));
    }

    /**
     * @test
     */
    public function Nxc3()
    {
        $pieces = [
            new Knight(Symbol::WHITE, 'b1'),
            new King(Symbol::WHITE, 'e1'),
            new Pawn(Symbol::WHITE, 'g6'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'c3'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nxc3')));
    }

    /**
     * @test
     */
    public function O_O()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG),
            new Knight(Symbol::WHITE, 'b1'),
            new Bishop(Symbol::WHITE, 'c1'),
            new Queen(Symbol::WHITE, 'd1'),
            new King(Symbol::WHITE, 'e1'),
            new Bishop(Symbol::WHITE, 'f1'),
            new Knight(Symbol::WHITE, 'g1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'b2'),
            new Pawn(Symbol::WHITE, 'c2'),
            new Pawn(Symbol::WHITE, 'd2'),
            new Pawn(Symbol::WHITE, 'e2'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::BLACK, 'a8', RookType::CASTLING_LONG),
            new Knight(Symbol::BLACK, 'b8'),
            new Bishop(Symbol::BLACK, 'c8'),
            new Queen(Symbol::BLACK, 'd8'),
            new King(Symbol::BLACK, 'e8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'a7'),
            new Pawn(Symbol::BLACK, 'b7'),
            new Pawn(Symbol::BLACK, 'c7'),
            new Pawn(Symbol::BLACK, 'd7'),
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
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
    }

    /**
     * @test
     */
    public function fix_check_with_Ke4()
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ke4')));
    }

    /**
     * @test
     */
    public function fix_check_with_Kg3()
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kg3')));
    }

    /**
     * @test
     */
    public function fix_check_with_Kg2()
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kg2')));
    }

    /**
     * @test
     */
    public function fix_check_with_Ke2()
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ke2')));
    }

    /**
     * @test
     */
    public function fix_check_with_Ke3()
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ke3')));
    }

    /**
     * @test
     */
    public function Kg2()
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kg2')));
    }

    /**
     * @test
     */
    public function Kxh2()
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
            new Rook(Symbol::BLACK, 'h2', RookType::CASTLING_SHORT),
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kxh2')));
    }

    /**
     * @test
     */
    public function Kxf3()
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
            new Rook(Symbol::BLACK, 'f3', RookType::CASTLING_SHORT), // rook not defended
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Kxf3')));
    }

    /**
     * @test
     */
    public function O_O_after_Nf6()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'e4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bb5'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function O_O_after_removing_threats()
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
            new Bishop(Symbol::BLACK, 'd6'),
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
    }

    /**
     * @test
     */
    public function check_castling_after_Rh1()
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

        $board->play(Convert::toObject(Symbol::WHITE, 'Rg1'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Rh1'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nd7'));
        $board->play(Convert::toObject(Symbol::WHITE, 'O-O')); // this won't be run
        $board->play(Convert::toObject(Symbol::WHITE, 'O-O-O')); // this will be run

        $whiteSquaresUsed = [
            'a2',
            'd5',
            'e4',
            'f3',
            'g2',
            'h2',
            'h1',
            'c1',
            'd1'
        ];

        $whiteSpace = [
            'd2', // rook
            'd3',
            'd4',
            'e1',
            'f1',
            'g1',
            'b3', // pawns
            'c6',
            'e6',
            'f5',
            'g4',
            'g3',
            'h3',
            'b1', // king
            'b2',
            'c2',
            'd2',
            'e1', // rook
            'f1',
            'g1'
        ];

        $whiteSpace = array_filter(array_unique($whiteSpace));
        sort($whiteSpace);
        $whiteAttack = [];

        $this->assertEquals($whiteSquaresUsed, $board->getSquares()->used->w);
        $this->assertEquals($whiteSpace, $board->getControl()->space->w);
        $this->assertEquals($whiteAttack, $board->getControl()->attack->w);
    }

    /**
     * @test
     */
    public function en_passant_f3()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'e2'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'e4'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7'),
            new King(Symbol::BLACK, 'e8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'f4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'exf3')));
    }

    /**
     * @test
     */
    public function en_passant_f6()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'e5'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'e7'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h7'),
            new King(Symbol::BLACK, 'e8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'f5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'exf6')));
    }

    /**
     * @test
     */
    public function en_passant_h3()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'e2'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'e7'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g4'),
            new Pawn(Symbol::BLACK, 'h7'),
            new King(Symbol::BLACK, 'e8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'h4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'gxh3')));
    }

    /**
     * @test
     */
    public function en_passant_g3()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'e2'),
            new Pawn(Symbol::WHITE, 'f2'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'e7'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Pawn(Symbol::BLACK, 'h4'),
            new King(Symbol::BLACK, 'e8'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'g4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'hxg3')));
    }

    /**
     * @test
     */
    public function another_en_passant_f6()
    {
        $board = new Board;

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'd5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bb4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'c5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qg4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ne7')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nbc6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'a3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bxc3+')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'bxc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Qc7')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rb1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bd3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'f5')));
        $pawn_e5 = $board->getPieceByPosition('e5');
        $pawn_e5->getLegalMoves(); // this creates the enPassantSquare property in the pawn's position object
        $this->assertEquals('f5', $pawn_e5->getEnPassantSquare());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'exf6')));
    }

    /**
     * @test
     */
    public function pawn_promotion()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h7'),
            new King(Symbol::WHITE, 'e1'),
            new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT),
            new Pawn(Symbol::BLACK, 'c7'),
            new Pawn(Symbol::BLACK, 'd7'),
            new Pawn(Symbol::BLACK, 'e7'),
            new Bishop(Symbol::BLACK, 'd6'),
            new King(Symbol::BLACK, 'e8')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'h8=Q')));
    }

    /**
     * @test
     */
    public function check()
    {
        $pieces = [
            new Rook(Symbol::WHITE, 'a7', RookType::CASTLING_LONG),
            new Pawn(Symbol::WHITE, 'd4'),
            new Queen(Symbol::WHITE, 'e3'),
            new King(Symbol::WHITE, 'g1'),
            new Pawn(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new King(Symbol::BLACK, 'e8'),
            new Knight(Symbol::BLACK, 'e4'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
            new Rook(Symbol::BLACK, 'g5', RookType::CASTLING_LONG),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT),
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ra8+')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Kd8')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Kf8')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ke7')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'h3')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Nc2')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rxg2+')));
        $this->assertEquals(true, $board->isCheck());
    }

    /**
     * @test
     */
    public function check_and_checkmate()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'd5'),
            new Queen(Symbol::WHITE, 'f5'),
            new King(Symbol::WHITE, 'g2'),
            new Pawn(Symbol::WHITE, 'h2'),
            new Rook(Symbol::WHITE, 'h8', RookType::CASTLING_LONG),
            new King(Symbol::BLACK, 'e7'),
            new Pawn(Symbol::BLACK, 'f7'),
            new Pawn(Symbol::BLACK, 'g7'),
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

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'd6+')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Kd7')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(false, $board->play(Convert::toObject(Symbol::BLACK, 'Ke6')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Kxd6')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Re8')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Kc7')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Re7+')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Kd8')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qd7#')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(true, $board->isMate());
    }
}
