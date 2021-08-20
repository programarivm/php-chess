<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\Castling\Rule as CastlingRule;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\Tests\AbstractUnitTestCase;

class LegalMovesTest extends AbstractUnitTestCase
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Ra6')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Rxa6')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'h6')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'hxg6')));
    }

    /**
     * @test
     */
    public function Nc3()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nc3')));
    }

    /**
     * @test
     */
    public function Nc6()
    {
        $board = new Board();
        $board->setTurn(Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6')));
    }

    /**
     * @test
     */
    public function Nf6()
    {
        $board = new Board();
        $board->setTurn(Symbol::BLACK);
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nf6')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nxc3')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'O-O')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Ke4')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Kg3')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Kg2')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Ke2')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Ke3')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Kg2')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Kxh2')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Kxf3')));
    }

    /**
     * @test
     */
    public function O_O_after_Nf6()
    {
        $board = new Board();

        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'c5'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6'));
        $board->play(Convert::toStdObj(Symbol::WHITE, 'Bb5'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nf6'));

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'O-O')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'O-O')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'f4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'exf3')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'f5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'exf6')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'h4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'gxh3')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'g4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'hxg3')));
    }

    /**
     * @test
     */
    public function another_en_passant_f6()
    {
        $board = new Board();

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'd5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nc3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bb4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'c5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Qg4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Ne7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nbc6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'a3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bxc3+')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'bxc3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Qc7')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Rb1')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'O-O')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Bd3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'f5')));
        $pawn_e5 = $board->getPieceByPosition('e5');
        $pawn_e5->getLegalMoves(); // this creates the enPassantSquare property in the pawn's position object
        $this->assertEquals('f5', $pawn_e5->getEnPassantSquare());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'exf6')));
    }

    /**
     * @test
     */
    public function en_passant_memory()
    {
        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'b2'),
            new Pawn(Symbol::WHITE, 'c5'),
            new Rook(Symbol::WHITE, 'd1', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'e4'),
            new Pawn(Symbol::BLACK, 'a7'),
            new Pawn(Symbol::BLACK, 'b7'),
            new Pawn(Symbol::BLACK, 'c7'),
            new King(Symbol::BLACK, 'g6'),
            new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_LONG),
        ];

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
        $board->setTurn(Symbol::BLACK);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'b5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'cxb6')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => false,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'h8=Q')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Ra8+')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->play(Convert::toStdObj(Symbol::BLACK, 'Kd8')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->play(Convert::toStdObj(Symbol::BLACK, 'Kf8')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Ke7')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'h3')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc2')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Rxg2+')));
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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd6+')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(false, $board->play(Convert::toStdObj(Symbol::BLACK, 'Kd7')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(false, $board->play(Convert::toStdObj(Symbol::BLACK, 'Ke6')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Kxd6')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Re8')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Kc7')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Re7+')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Kd8')));
        $this->assertEquals(false, $board->isCheck());
        $this->assertEquals(false, $board->isMate());
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Qd7#')));
        $this->assertEquals(true, $board->isCheck());
        $this->assertEquals(true, $board->isMate());
    }

    /**
     * @test
     */
    public function captures()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bb4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'c3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Bxc3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'bxc3')));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Be2_Be7()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Be2')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Be7')));
        // short castling, O-O
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Kg1')));
    }

    /**
     * @test
     */
    public function Nf3_Nf6_d3_d6_Nfd2()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'd6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nfd2')));
    }

    /**
     * @test
     */
    public function Nf3_Nf6_d3_d6_Nf3d2()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'Nf6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'd3')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'd6')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'Nf3d2')));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_dxe6()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'd5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'exd5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'dxe6')));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_then_get_piece()
    {
        $board = new Board();
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'd5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::WHITE, 'exd5')));
        $this->assertEquals(true, $board->play(Convert::toStdObj(Symbol::BLACK, 'e5')));

        $this->assertEquals('P', $board->getPieceByPosition('d5')->getIdentity());
    }
}
