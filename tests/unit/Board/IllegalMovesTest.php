<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\Tests\AbstractUnitTestCase;

class IllegalMovesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function normal_turn()
    {
        $board = new Board();

        $this->assertSame($board->getTurn(), 'w');
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertSame($board->getTurn(), 'b');
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertSame($board->getTurn(), 'w');
    }

    /**
     * @test
     */
    public function wrong_turn()
    {
        $board = new Board();

        $this->assertSame($board->getTurn(), 'w');
        $this->assertFalse($board->play('b', 'e4'));
        $this->assertSame($board->getTurn(), 'w');
        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertSame($board->getTurn(), 'w');
        $this->assertFalse($board->play('w', 'O-O-O'));
        $this->assertSame($board->getTurn(), 'w');
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertSame($board->getTurn(), 'b');
        $this->assertFalse($board->play('w', 'e5'));
        $this->assertSame($board->getTurn(), 'b');
        $this->assertFalse($board->play('w', 'Nf3'));
        $this->assertSame($board->getTurn(), 'b');
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertSame($board->getTurn(), 'w');
        $this->assertFalse($board->play('b', 'Nc6'));
    }

    /**
     * @test
     */
    public function Qg5()
    {
        $board = new Board();
        $this->assertFalse($board->play('b', 'Qg5'));
    }

    /**
     * @test
     */
    public function Ra6()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function Rxa6()
    {
        $board = new Board();
        $this->assertFalse($board->play('b', 'Rxa6'));
    }

    /**
     * @test
     */
    public function Bxe5()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'Bxe5'));
    }

    /**
     * @test
     */
    public function exd4()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'exd4'));
    }

    /**
     * @test
     */
    public function Nxd2()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'Nxd2'));
    }

    /**
     * @test
     */
    public function Nxc3()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'Nxc3'));
    }

    /**
     * @test
     */
    public function white_O_O()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function white_O_O_O()
    {
        $board = new Board();
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function black_O_O()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $this->assertFalse($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function Kf4()
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

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function Kf4_check()
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

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function Kf2_check()
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

        $this->assertFalse($board->play('w', 'Kf2'));
    }

    /**
     * @test
     */
    public function Re7_check()
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

        $this->assertFalse($board->play('w', 'Re7'));
    }

    /**
     * @test
     */
    public function a4_check()
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

        $this->assertFalse($board->play('w', 'a4'));
    }

    /**
     * @test
     */
    public function Kxf2()
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
            new Rook('b', 'f2', RookType::CASTLING_SHORT), // rook defended by knight
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

        $this->assertFalse($board->play('w', 'Kxf2'));
    }

    /**
     * @test
     */
    public function white_O_O_after_Nc6()
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
    public function white_O_O_O_after_Nf6()
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
    public function castling_threatening_f1()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'd4'),
            new Pawn('w', 'e4'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new King('w', 'e1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Bishop('b', 'a6'), // bishop threatening f1
            new King('b', 'e8'),
            new Bishop('b', 'f8'),
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

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function castling_threatening_f1_g1()
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
            new Bishop('b', 'a6'), // bishop threatening f1
            new King('b', 'e8'),
            new Bishop('b', 'c5'), // bishop threatening g1
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

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function castling_threatening_g1()
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
            new Bishop('b', 'c5'), // bishop threatening g1
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

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function castling_threatening_c1()
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
            new Bishop('b', 'f4'), // bishop threatening c1
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

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function castling_threatening_d1_f1()
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
            new Bishop('b', 'f8'),
            new Knight('b', 'e3'), // knight threatening d1 and f1
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

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function castling_threatening_b1_f1()
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
            new Bishop('b', 'f8'),
            new Knight('b', 'd2'), // knight threatening b1 and f1
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

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function castling_threatening_b1_d1()
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
            new Bishop('b', 'f8'),
            new Knight('b', 'c3'), // knight threatening b1 and d1
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

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function O_O_after_Kf1()
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
            new Bishop('b', 'f8'),
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

        $this->assertTrue($board->play('w', 'Kf1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Ke1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function O_O_after_Rg1()
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
            new Bishop('b', 'f8'),
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

        $this->assertTrue($board->play('w', 'Rg1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Rh1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function O_O_RuyLopez()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'Nf6');
        $board->play('w', 'Ke2');
        $board->play('b', 'Bb4');
        $board->play('w', 'Ke1');
        $board->play('b', 'Ke7');
        $board->play('w', 'Nc3');
        $board->play('b', 'Ke8');

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function opponent_threatening_castling_sqs()
    {
        $pieces = [
            new Pawn('w', 'a2'),
            new Pawn('w', 'c2'),
            new Pawn('w', 'c3'),
            new Pawn('w', 'd4'),
            new Pawn('w', 'f2'),
            new Pawn('w', 'g2'),
            new Pawn('w', 'h2'),
            new Rook('w', 'a1', RookType::CASTLING_LONG),
            new King('w', 'e1'),
            new Knight('w', 'g1'),
            new Rook('w', 'h1', RookType::CASTLING_SHORT),
            new Bishop('w', 'a3'),
            new Bishop('w', 'd3'),
            new Pawn('b', 'a7'),
            new Pawn('b', 'b6'),
            new Pawn('b', 'c7'),
            new Pawn('b', 'e6'),
            new Pawn('b', 'g7'),
            new Pawn('b', 'h6'),
            new Rook('b', 'a8', RookType::CASTLING_LONG),
            new Bishop('b', 'c8'),
            new Queen('b', 'd8'),
            new King('b', 'e8'),
            new Rook('b', 'h8', RookType::CASTLING_SHORT),
            new Knight('b', 'd7'),
            new Knight('b', 'f6')
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

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertFalse($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function falsly_game()
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
}
