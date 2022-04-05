<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
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

class StatusTest extends AbstractUnitTestCase
{
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

    /**
     * @test
     */
    public function movetext_in_exchange_ruy_lopez()
    {
        $board = (new ExchangeRuyLopez(new Board()))->play();

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 a6 4.Bxc6 dxc6 5.d4 exd4 6.Qxd4 Qxd4 7.Nxd4';

        $this->assertSame($expected, $board->getMovetext());
    }

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
    public function kings_legal_moves_in_benko_gambit()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $king = $board->getPieceBySq('f1');

        $expected = [ 'e1', 'e2', 'g2' ];

        $this->assertSame($expected, $king->getSqs());
    }

    /**
     * @test
     */
    public function count_pieces_in_benko_gambit()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $this->assertSame(14, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertSame(13, count($board->getPiecesByColor(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function queens_legal_moves_in_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $queen = $board->getPieceBySq('d1');

        $expected = [ 'e1', 'c2', 'b3' ];

        $this->assertSame($expected, $queen->getSqs());
    }

    /**
     * @test
     */
    public function count_pieces_in_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $this->assertSame(15, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertSame(15, count($board->getPiecesByColor(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function count_pieces_in_open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $this->assertSame(15, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertSame(15, count($board->getPiecesByColor(Symbol::BLACK)));
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
}
