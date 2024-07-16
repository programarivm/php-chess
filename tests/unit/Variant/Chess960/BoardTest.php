<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Chess960\Board;
use Chess\Variant\Chess960\StartPosition;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function pieces()
    {
        $startPos = (new StartPosition())->create();
        $board = new Board($startPos);
        $pieces = $board->pieces();

        $this->assertSame(32, count($pieces));
    }

    /**
     * @test
     */
    public function castling_rule_RBBKRQNN()
    {
        $startPos = ['R', 'B', 'B', 'K', 'R', 'Q', 'N', 'N'];

        $castlingRule = (new Board($startPos))->castlingRule;

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'f1', 'g1' ],
                        'attack' => [ 'd1', 'e1', 'f1', 'g1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'g1',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'b1', 'c1' ],
                        'attack' => [ 'c1', 'd1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'c1',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'f1',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'a1',
                            'next' => 'd1',
                        ],
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'f8', 'g8' ],
                        'attack' => [ 'd8', 'e8', 'f8', 'g8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'g8',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'b8', 'c8' ],
                        'attack' => [ 'c8', 'd8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'c8',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'f8',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'a8',
                            'next' => 'd8',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule->rule);
    }

    /**
     * @test
     */
    public function castling_rule_QRBKRBNN()
    {
        $startPos = ['Q', 'R', 'B', 'K', 'R', 'B', 'N', 'N'];

        $castlingRule = (new Board($startPos))->castlingRule;

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'f1', 'g1' ],
                        'attack' => [ 'd1', 'e1', 'f1', 'g1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'g1',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'c1' ],
                        'attack' => [ 'c1', 'd1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'c1',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'f1',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'b1',
                            'next' => 'd1',
                        ],
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'f8', 'g8' ],
                        'attack' => [ 'd8', 'e8', 'f8', 'g8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'g8',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'c8' ],
                        'attack' => [ 'c8', 'd8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'c8',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'f8',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'b8',
                            'next' => 'd8',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule->rule);
    }

    /**
     * @test
     */
    public function castling_rule_BQNRKBRN()
    {
        $startPos = ['B', 'Q', 'N', 'R', 'K', 'B', 'R', 'N'];

        $castlingRule = (new Board($startPos))->castlingRule;

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'f1' ],
                        'attack' => [ 'e1', 'f1', 'g1' ],
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'g1',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'c1' ],
                        'attack' => [ 'c1', 'd1', 'e1' ],
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'c1',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'g1',
                            'next' => 'f1',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'd1',
                        ],
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'f8' ],
                        'attack' => [ 'e8', 'f8', 'g8' ],
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'g8',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'c8' ],
                        'attack' => [ 'c8', 'd8', 'e8' ],
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'c8',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'g8',
                            'next' => 'f8',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'd8',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule->rule);
    }

    /**
     * @test
     */
    public function play_QRBKRBNN_e4_e5()
    {
        $startPos = ['Q', 'R', 'B', 'K', 'R', 'B', 'N', 'N'];

        $board = new Board($startPos);

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ ' q ', ' r ', ' b ', ' k ', ' r ', ' b ', ' n ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' R ', ' B ', ' K ', ' R ', ' B ', ' N ', ' N ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_BBNRKRQN_e4_e5()
    {
        $startPos = ['B', 'B', 'N', 'R', 'K', 'R', 'Q', 'N'];

        $board = new Board($startPos);

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ ' b ', ' b ', ' n ', ' r ', ' k ', ' r ', ' q ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' B ', ' B ', ' N ', ' R ', ' K ', ' R ', ' Q ', ' N ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_NRNQKBBR_e4_Nd6_Bc4_e6_f3_Qe7_Bf2()
    {
        $startPos = ['N', 'R', 'N', 'Q', 'K', 'B', 'B', 'R'];

        $board = new Board($startPos);

        $board->play('w', 'e4');
        $board->play('b', 'Nd6');

        $board->play('w', 'Bc4');
        $board->play('b', 'e6');

        $board->play('w', 'f3');
        $board->play('b', 'Qe7');

        $board->play('w', 'Bf2');

        $expected = [
            7 => [ ' n ', ' r ', ' . ', ' . ', ' k ', ' b ', ' b ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' q ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' n ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' B ', ' P ', ' P ' ],
            0 => [ ' N ', ' R ', ' N ', ' Q ', ' K ', ' . ', ' . ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_NRNQKBBR_e4_Nd6_Bc4_e6_f3_Qe7_Bf2_O_O_O()
    {
        $startPos = ['N', 'R', 'N', 'Q', 'K', 'B', 'B', 'R'];

        $board = new Board($startPos);

        $board->play('w', 'e4');
        $board->play('b', 'Nd6');

        $board->play('w', 'Bc4');
        $board->play('b', 'e6');

        $board->play('w', 'f3');
        $board->play('b', 'Qe7');

        $board->play('w', 'Bf2');
        $board->play('b', 'O-O-O');

        $expected = [
            7 => [ ' n ', ' . ', ' k ', ' r ', ' . ', ' b ', ' b ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' q ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' n ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' B ', ' P ', ' P ' ],
            0 => [ ' N ', ' R ', ' N ', ' Q ', ' K ', ' . ', ' . ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_BBRQNNKR_Ne3_Ne6_O_O()
    {
        $startPos = ['B', 'B', 'R', 'Q', 'N', 'N', 'K', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Ne3'));
        $this->assertTrue($board->play('b', 'Ne6'));
        $this->assertTrue($board->play('w', 'O-O'));

        $expected = [
            7 => [ ' b ', ' b ', ' r ', ' q ', ' n ', ' . ', ' k ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' B ', ' B ', ' R ', ' Q ', ' N ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_BQNRKBRN_e3_g6_Bc4_Bh6()
    {
        $startPos = ['B', 'Q', 'N', 'R', 'K', 'B', 'R', 'N'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'g6'));
        $this->assertTrue($board->play('w', 'Bc4'));
        $this->assertTrue($board->play('b', 'Bh6'));

        $expected = [
            7 => [ ' b ', ' q ', ' n ', ' r ', ' k ', ' . ', ' r ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ', ' b ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' B ', ' Q ', ' N ', ' R ', ' K ', ' . ', ' R ', ' N ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_BQNRKBRN_e3_g6_Bc4_Bh6_a3()
    {
        $startPos = ['B', 'Q', 'N', 'R', 'K', 'B', 'R', 'N'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'g6'));
        $this->assertTrue($board->play('w', 'Bc4'));
        $this->assertTrue($board->play('b', 'Bh6'));
        $this->assertTrue($board->play('w', 'a3'));

        $expected = [
            7 => [ ' b ', ' q ', ' n ', ' r ', ' k ', ' . ', ' r ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ', ' b ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' B ', ' Q ', ' N ', ' R ', ' K ', ' . ', ' R ', ' N ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_NRQBBKRN_O_O()
    {
        $startPos = ['N', 'R', 'Q', 'B', 'B', 'K', 'R', 'N'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'O-O'));

        $expected = [
            7 => [ ' n ', ' r ', ' q ', ' b ', ' b ', ' k ', ' r ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' N ', ' R ', ' Q ', ' B ', ' B ', ' R ', ' K ', ' N ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_BNQRKRNB_Nf3_b6_O_O()
    {
        $startPos = ['B', 'N', 'Q', 'R', 'K', 'R', 'N', 'B'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'b6'));
        $this->assertTrue($board->play('w', 'O-O'));

        $expected = [
            7 => [ ' b ', ' n ', ' q ', ' r ', ' k ', ' r ', ' n ', ' b ' ],
            6 => [ ' p ', ' . ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' B ', ' N ', ' Q ', ' R ', ' . ', ' R ', ' K ', ' B ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_QBBNRNKR_Nf1e3_Nd8e6_Kf1()
    {
        $startPos = ['Q', 'B', 'B', 'N', 'R', 'N', 'K', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Nf1e3'));
        $this->assertTrue($board->play('b', 'Nd8e6'));
        $this->assertTrue($board->play('w', 'Kf1'));

        $expected = [
            7 => [ ' q ', ' b ', ' b ', ' . ', ' r ', ' n ', ' k ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' B ', ' B ', ' N ', ' R ', ' K ', ' . ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_QBBNRNKR_Nf1e3_Nd8e6_O_O()
    {
        $startPos = ['Q', 'B', 'B', 'N', 'R', 'N', 'K', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Nf1e3'));
        $this->assertTrue($board->play('b', 'Nd8e6'));
        $this->assertTrue($board->play('w', 'O-O'));

        $expected = [
            7 => [ ' q ', ' b ', ' b ', ' . ', ' r ', ' n ', ' k ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' B ', ' B ', ' N ', ' R ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_RKRNBQNB_Ne3_Ne6_O_O_O()
    {
        $startPos = ['R', 'K', 'R', 'N', 'B', 'Q', 'N', 'B'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Ne3'));
        $this->assertTrue($board->play('b', 'Ne6'));
        $this->assertFalse($board->play('w', 'O-O-O'));

        $expected = [
            7 => [ ' r ', ' k ', ' r ', ' . ', ' b ', ' q ', ' n ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' K ', ' R ', ' . ', ' B ', ' Q ', ' N ', ' B ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_RKNBBQNR_Nd3_Nf6_Kc1()
    {
        $startPos = ['R', 'K', 'N', 'B', 'B', 'Q', 'N', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Nd3'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Kc1'));

        $expected = [
            7 => [ ' r ', ' k ', ' n ', ' b ', ' b ', ' q ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' K ', ' B ', ' B ', ' Q ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_RKNBBQNR_Nd3_Nf6_O_O()
    {
        $startPos = ['R', 'K', 'N', 'B', 'B', 'Q', 'N', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Nd3'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertFalse($board->play('w', 'O-O'));

        $expected = [
            7 => [ ' r ', ' k ', ' n ', ' b ', ' b ', ' q ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' K ', ' . ', ' B ', ' B ', ' Q ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_RKNBBQNR_Nd3_Nf6_O_O_O()
    {
        $startPos = ['R', 'K', 'N', 'B', 'B', 'Q', 'N', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'Nd3'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertFalse($board->play('w', 'O-O-O'));

        $expected = [
            7 => [ ' r ', ' k ', ' n ', ' b ', ' b ', ' q ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' n ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' K ', ' . ', ' B ', ' B ', ' Q ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_NBQNRKBR_h3_h6_Bh2_Bh7_Kg1()
    {
        $startPos = ['N', 'B', 'Q', 'N', 'R', 'K', 'B', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'h3'));
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertTrue($board->play('w', 'Bh2'));
        $this->assertTrue($board->play('b', 'Bh7'));
        $this->assertTrue($board->play('w', 'Kg1'));

        $expected = [
            7 => [ ' n ', ' b ', ' q ', ' n ', ' r ', ' k ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' b ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' P ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' B ' ],
            0 => [ ' N ', ' B ', ' Q ', ' N ', ' R ', ' . ', ' K ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_NBQNRKBR_h3_h6_Bh2_Bh7_Kg1_undo()
    {
        $startPos = ['N', 'B', 'Q', 'N', 'R', 'K', 'B', 'R'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'h3'));
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertTrue($board->play('w', 'Bh2'));
        $this->assertTrue($board->play('b', 'Bh7'));
        $this->assertTrue($board->play('w', 'Kg1'));

        $board = $board->undo();

        $expected = [
            7 => [ ' n ', ' b ', ' q ', ' n ', ' r ', ' k ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' b ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' P ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' B ' ],
            0 => [ ' N ', ' B ', ' Q ', ' N ', ' R ', ' K ', ' . ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_RNNQBKRB_O_O_undo()
    {
        $startPos = ['R', 'N', 'N', 'Q', 'B', 'K', 'R', 'B'];

        $board = new Board($startPos);

        $this->assertTrue($board->play('w', 'O-O'));

        $board = $board->undo();

        $expected = [
            7 => [ ' r ', ' n ', ' n ', ' q ', ' b ', ' k ', ' r ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' N ', ' Q ', ' B ', ' K ', ' R ', ' B ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }
}
