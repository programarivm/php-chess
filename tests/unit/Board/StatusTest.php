<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\Castling\Rule as CastlingRule;
use Chess\PGN\Move;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\RuyLopez\Exchange as ExchangeRuyLopez;
use Chess\Tests\Sample\Opening\RuyLopez\LucenaDefense as LucenaDefense;
use Chess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;
use Chess\Tests\Sample\Opening\Benoni\FianchettoVariation as BenoniFianchettoVariation;

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
                        'identity' => 'B',
                        'position' => 'b5',
                    ],
                    'captured' => (object) [
                        'identity' => 'N',
                        'position' => 'c6',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'identity' => 'Q',
                        'position' => 'd1',
                    ],
                    'captured' => (object) [
                        'identity' => 'P',
                        'position' => 'd4',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'identity' => 'N',
                        'position' => 'f3',
                    ],
                    'captured' => (object) [
                        'identity' => 'Q',
                        'position' => 'd4',
                    ],
                ],
            ],
            'b' => [
                (object) [
                    'capturing' => (object) [
                        'identity' => 'P',
                        'position' => 'd7',
                    ],
                    'captured' => (object) [
                        'identity' => 'B',
                        'position' => 'c6',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'identity' => 'P',
                        'position' => 'e5',
                    ],
                    'captured' => (object) [
                        'identity' => 'P',
                        'position' => 'd4',
                    ],
                ],
                (object) [
                    'capturing' => (object) [
                        'identity' => 'Q',
                        'position' => 'd8',
                    ],
                    'captured' => (object) [
                        'identity' => 'Q',
                        'position' => 'd4',
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

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function history_in_lucena_defense()
    {
        $board = (new LucenaDefense(new Board()))->play();

        $expected = [
            (object) [
                'position' => 'e2',
                'move' => (object) [
                    'pgn' => 'e4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::WHITE,
                    'identity' => Symbol::PAWN,
                    'position' => (object) [
                        'current' => 'e',
                        'next' => 'e4',
                    ],
                ],
            ],
            (object) [
                'position' => 'e7',
                'move' => (object) [
                    'pgn' => 'e5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Symbol::BLACK,
                    'identity' => Symbol::PAWN,
                    'position' => (object) [
                        'current' => 'e',
                        'next' => 'e5',
                    ],
                ],
            ],
            (object) [
                'position' => 'g1',
                'move' => (object) [
                    'pgn' => 'Nf3',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Symbol::WHITE,
                    'identity' => Symbol::KNIGHT,
                    'position' => (object) [
                        'current' => null,
                        'next' => 'f3',
                    ],
                ],
            ],
            (object) [
                'position' => 'b8',
                'move' => (object) [
                    'pgn' => 'Nc6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Symbol::BLACK,
                    'identity' => Symbol::KNIGHT,
                    'position' => (object) [
                        'current' => null,
                        'next' => 'c6',
                    ],
                ],
            ],
            (object) [
                'position' => 'f1',
                'move' => (object) [
                    'pgn' => 'Bb5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PIECE,
                    'color' => Symbol::WHITE,
                    'identity' => Symbol::BISHOP,
                    'position' => (object) [
                        'current' => null,
                        'next' => 'b5',
                    ],
                ],
            ],
            (object) [
                'position' => 'f8',
                'move' => (object) [
                    'pgn' => 'Be7',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PIECE,
                    'color' => Symbol::BLACK,
                    'identity' => Symbol::BISHOP,
                    'position' => (object) [
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

        $king = $board->getPieceByPosition('f1');

        $expected = [ 'e1', 'e2', 'g2' ];

        $this->assertEquals($expected, $king->getLegalMoves());
    }

    /**
     * @test
     */
    public function count_pieces_in_benko_gambit()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $this->assertEquals(14, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertEquals(13, count($board->getPiecesByColor(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function queens_legal_moves_in_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $queen = $board->getPieceByPosition('d1');

        $expected = [ 'e1', 'c2', 'b3' ];

        $this->assertEquals($expected, $queen->getLegalMoves());
    }

    /**
     * @test
     */
    public function count_pieces_in_fianchetto_variation()
    {
        $board = (new BenoniFianchettoVariation(new Board()))->play();

        $this->assertEquals(15, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertEquals(15, count($board->getPiecesByColor(Symbol::BLACK)));
    }

    /**
     * @test
     */
    public function count_pieces_in_open_sicilian()
    {
        $board = (new OpenSicilian(new Board()))->play();

        $this->assertEquals(15, count($board->getPiecesByColor(Symbol::WHITE)));
        $this->assertEquals(15, count($board->getPiecesByColor(Symbol::BLACK)));
    }
}
