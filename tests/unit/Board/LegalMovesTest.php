<?php

namespace Chess\Tests\Unit\Board;

use Chess\Ascii;
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

        $this->assertTrue($board->play('w', 'Ra6'));
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

        $this->assertTrue($board->play('w', 'Rxa6'));
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

        $this->assertTrue($board->play('b', 'h6'));
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

        $this->assertTrue($board->play('b', 'hxg6'));
    }

    /**
     * @test
     */
    public function Nc3()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'Nc3'));
    }

    /**
     * @test
     */
    public function Nc6()
    {
        $board = new Board();
        $board->setTurn(Symbol::BLACK);
        $this->assertTrue($board->play('b', 'Nc6'));
    }

    /**
     * @test
     */
    public function Nf6()
    {
        $board = new Board();
        $board->setTurn(Symbol::BLACK);
        $this->assertTrue($board->play('b', 'Nf6'));
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

        $this->assertTrue($board->play('w', 'Nxc3'));
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

        $this->assertTrue($board->play('b', 'O-O'));
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

        $this->assertTrue($board->play('w', 'Ke4'));
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

        $this->assertTrue($board->play('w', 'Kg3'));
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

        $this->assertTrue($board->play('w', 'Kg2'));
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

        $this->assertTrue($board->play('w', 'Ke2'));
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

        $this->assertTrue($board->play('w', 'Ke3'));
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

        $this->assertTrue($board->play('w', 'Kg2'));
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

        $this->assertTrue($board->play('w', 'Kxh2'));
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

        $this->assertTrue($board->play('w', 'Kxf3'));
    }

    /**
     * @test
     */
    public function O_O_after_Nf6()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'c5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'Nf6');

        $this->assertTrue($board->play('w', 'O-O'));
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

        $this->assertTrue($board->play('w', 'O-O'));
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

        $this->assertTrue($board->play('w', 'f4'));
        $this->assertTrue($board->play('b', 'exf3'));
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

        $this->assertTrue($board->play('b', 'f5'));
        $this->assertTrue($board->play('w', 'exf6'));
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

        $this->assertTrue($board->play('w', 'h4'));
        $this->assertTrue($board->play('b', 'gxh3'));
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

        $this->assertTrue($board->play('w', 'g4'));
        $this->assertTrue($board->play('b', 'hxg3'));
    }

    /**
     * @test
     */
    public function another_en_passant_f6()
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
        $pawn_e5 = $board->getPieceByPosition('e5');
        $pawn_e5->getLegalMoves(); // this creates the enPassantSquare property in the pawn's position object
        $this->assertSame('f5', $pawn_e5->getEnPassantSquare());
        $this->assertTrue($board->play('w', 'exf6'));
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

        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'cxb6'));
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

        $this->assertTrue($board->play('w', 'h8=Q'));
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

        $this->assertTrue($board->play('w', 'Ra8+'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->play('b', 'Kd8'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->play('b', 'Kf8'));
        $this->assertTrue($board->isCheck());
        $this->assertTrue($board->play('b', 'Ke7'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('w', 'h3'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->play('b', 'Nc2'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('b', 'Rxg2+'));
        $this->assertTrue($board->isCheck());
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
    public function captures()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'Bb4'));
        $this->assertTrue($board->play('w', 'c3'));
        $this->assertTrue($board->play('b', 'Bxc3'));
        $this->assertTrue($board->play('w', 'bxc3'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Be2_Be7()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nc6'));
        $this->assertTrue($board->play('w', 'Be2'));
        $this->assertTrue($board->play('b', 'Be7'));
        // short castling, O-O
        $this->assertTrue($board->play('w', 'Kg1'));
    }

    /**
     * @test
     */
    public function Nf3_Nf6_d3_d6_Nfd2()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Nfd2'));
    }

    /**
     * @test
     */
    public function Nf3_Nf6_d3_d6_Nf3d2()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Nf3d2'));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_dxe6()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'exd5'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertTrue($board->play('w', 'dxe6'));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_then_get_piece()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'exd5'));
        $this->assertTrue($board->play('b', 'e5'));

        $this->assertSame('P', $board->getPieceByPosition('d5')->getIdentity());
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_d4_cxd4_Nxd4_Nf6_Nc3_Nc6()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'c5'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'cxd4'));
        $this->assertTrue($board->play('w', 'Nxd4'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Nc3'));
        $this->assertTrue($board->play('b', 'Nc6'));
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_d4_cxd4_Nxd4_Nf6_then_get_piece()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'c5'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'cxd4'));
        $this->assertTrue($board->play('w', 'Nxd4'));
        $this->assertTrue($board->play('b', 'Nf6'));

        $this->assertNotEmpty($board->getPieceByPosition('b1')->getLegalMoves());
    }
}
