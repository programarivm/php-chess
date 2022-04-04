<?php

namespace Chess\Tests\Unit\Board;

use Chess\Ascii;
use Chess\Board;
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
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new Queen('w', 'd1'),
            new King('w', 'e1'),
            new Bishop('w', 'f1'),
            new Knight('w', 'g1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('w', 'b2'),
            new Pawn('w', 'c2'),
            new Pawn('w', 'd2'),
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('b', 'a8', RookType::CASTLING_LONG),
            new Knight('b', 'b8'),
            new Bishop('b', 'c8'),
            new Queen('b', 'd8'),
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b7'),
            new Pawn('b', 'c7'),
            new Pawn('b', 'd7'),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function Rxa6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new King('w', 'e1'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Rxa6'));
    }

    /**
     * @test
     */
    public function h6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new King('w', 'e1'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'h6'));
    }

    /**
     * @test
     */
    public function hxg6()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new King('w', 'e1'),
            new Pawn('w', 'g6'),
            new King('b', 'e8'),
            new Bishop('b', 'a6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);
        $board->setTurn('b');

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
        $board->setTurn('b');
        $this->assertTrue($board->play('b', 'Nc6'));
    }

    /**
     * @test
     */
    public function Nf6()
    {
        $board = new Board();
        $board->setTurn('b');
        $this->assertTrue($board->play('b', 'Nf6'));
    }

    /**
     * @test
     */
    public function Nxc3()
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

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Nxc3'));
    }

    /**
     * @test
     */
    public function O_O()
    {
        $pieces = [
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new Knight('w', 'b1'),
            new Bishop('w', 'c1'),
            new Queen('w', 'd1'),
            new King('w', 'e1'),
            new Bishop('w', 'f1'),
            new Knight('w', 'g1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('w', 'a2'),
            new Pawn('w', 'b2'),
            new Pawn('w', 'c2'),
            new Pawn('w', 'd2'),
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('b', 'a8', RookType::CASTLING_LONG),
            new Knight('b', 'b8'),
            new Bishop('b', 'c8'),
            new Queen('b', 'd8'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b7'),
            new Pawn('b', 'c7'),
            new Pawn('b', 'd7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function fix_check_with_Ke4()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Ke4'));
    }

    /**
     * @test
     */
    public function fix_check_with_Kg3()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Kg3'));
    }

    /**
     * @test
     */
    public function fix_check_with_Kg2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function fix_check_with_Ke2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Ke2'));
    }

    /**
     * @test
     */
    public function fix_check_with_Ke3()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'f3'), // in check!
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Ke3'));
    }

    /**
     * @test
     */
    public function Kg2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function Kxh2()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'h2', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'Kxh2'));
    }

    /**
     * @test
     */
    public function Kxf3()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f3', RookType::CASTLING_SHORT), // rook not defended
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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
            new Pawn('w', 'a2'),
            new Pawn('w', 'd5'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f3'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new King('b', 'e8'),
            new Bishop('b', 'd6'),
            new Knight('b', 'g8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function en_passant_f3()
    {
        $pieces = [
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('b', 'e4'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = [
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
            new Pawn('w', 'e5'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'f5'));
        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function en_passant_h3()
    {
        $pieces = [
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g4'),
            new Pawn('b', 'h7'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = [
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
            new Pawn('w', 'e2'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h4'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT)
        ];

        $castling = [
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
        $pawn_e5 = $board->getPieceBySq('e5');
        $pawn_e5->getSquares(); // this creates the enPassantSquare property in the pawn's position object
        $this->assertSame('f5', $pawn_e5->getEnPassantSq());
        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function en_passant_memory()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'b2'),
            new Pawn('w', 'c5'),
            new Rook('w', 'd1', RookType::CASTLING_LONG),
            new King('w', 'e4'),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b7'),
            new Pawn('b', 'c7'),
            new King('b', 'g6'),
            new Rook('b', 'h8', RookType::CASTLING_LONG),
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'cxb6'));
    }

    /**
     * @test
     */
    public function pawn_promotion()
    {
        $pieces = [
            new Pawn('w', 'g2'),
            new Pawn('w', 'h7'),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Pawn('b', 'c7'),
            new Pawn('b', 'd7'),
            new Pawn('b', 'e7'),
            new Bishop('b', 'd6'),
            new King('b', 'e8')
        ];

        $castling = [
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

        $board = new Board($pieces, $castling);

        $this->assertTrue($board->play('w', 'h8=Q'));
    }

    /**
     * @test
     */
    public function check()
    {
        $pieces = [
            new Rook('w', 'a7', RookType::CASTLING_LONG),
            new Pawn('w', 'd4'),
            new Queen('w', 'e3'),
            new King('w', 'g1'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new King('b', 'e8'),
            new Knight('b', 'e4'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Rook('b', 'g5', RookType::CASTLING_LONG),
            new Rook('b', 'h8', RookType::CASTLING_SHORT),
            new Pawn('b', 'h7')
        ];

        $castling = [
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
            new Pawn('w', 'd5'),
            new Queen('w', 'f5'),
            new King('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'h8', RookType::CASTLING_LONG),
            new King('b', 'e7'),
            new Pawn('b', 'f7'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h7')
        ];

        $castling = [
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

        $this->assertSame('P', $board->getPieceBySq('d5')->getId());
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

        $this->assertNotEmpty($board->getPieceBySq('b1')->getSquares());
    }

    /**
     * @test
     */
    public function king_and_queen_vs_king_stalemate()
    {
        $pieces = [
            new King('b', 'h1'),
            new King('w', 'a8'),
            new Queen('w', 'f2'),
        ];

        $castling = [
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

        $board = (new Board($pieces, $castling))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function king_and_pawn_vs_king_stalemate()
    {
        $pieces = [
            new King('w', 'f6'),
            new Pawn('w', 'f7'),
            new King('b', 'f8'),
        ];

        $castling = [
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

        $board = (new Board($pieces, $castling))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function king_and_rook_vs_king_and_bishop_stalemate()
    {
        $pieces = [
            new King('w', 'b6'),
            new Rook('w', 'h8', RookType::CASTLING_LONG),
            new King('b', 'a8'),
            new Bishop('b', 'b8'),
        ];

        $castling = [
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

        $board = (new Board($pieces, $castling))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function endgame_stalemate()
    {
        $pieces = [
            new King('w', 'g1'),
            new Queen('w', 'd1'),
            new Rook('w', 'a5', RookType::CASTLING_SHORT),
            new Rook('w', 'b7', RookType::CASTLING_LONG),
            new Pawn('w', 'f6'),
            new Pawn('w', 'g5'),
            new King('b', 'e6'),
        ];

        $castling = [
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

        $board = (new Board($pieces, $castling))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }
}
