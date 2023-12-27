<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\Movetext\SanMovetext;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\Rule\CastlingRule;

class BoardTest extends AbstractUnitTestCase
{
    static private $size;

    public static function setUpBeforeClass(): void
    {
        self::$size = [
            'files' => 8,
            'ranks' => 8,
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Play sample games.
    |---------------------------------------------------------------------------
    |
    | Plays the sample games that are found in the tests/data/pgn folder.
    |
    */

    /**
     * @test
     */
    public function play_games()
    {
        $move = new Move();
        foreach (new \DirectoryIterator(self::DATA_FOLDER."/pgn/") as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $filename = $fileInfo->getFilename();
            $movetext = file_get_contents(self::DATA_FOLDER."/pgn/$filename");
            $san = new SanMovetext($move, $movetext);
            if ($san->validate()) {
                $board = (new StrToBoard('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -'))
                    ->create();
                foreach ($san->getMoves() as $key => $val) {
                    $this->assertTrue($board->play($board->getTurn(), $val));
                }
            }
        }
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
    public function get_captures_in_C68()
    {
        $C68 = file_get_contents(self::DATA_FOLDER.'/sample/C68.pgn');

        $board = (new SanPlay($C68))->validate()->getBoard();

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
    | getCastle()
    |--------------------------------------------------------------------------
    |
    | Gets the castle status.
    |
    */

    /**
     * @test
     */
    public function get_castle_in_C67()
    {
        $C67 = file_get_contents(self::DATA_FOLDER.'/sample/C67.pgn');

        $board = (new SanPlay($C67))->validate()->getBoard();

        $expected = 'kq';

        $this->assertSame($expected, $board->getCastlingAbility());
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
    public function get_history_in_D06()
    {
        $D06 = file_get_contents(self::DATA_FOLDER.'/sample/D06.pgn');
        $D06 = str_replace("\n", "", $D06);

        $board = (new SanPlay($D06))->validate()->getBoard();

        $expected = [
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'd2',
                'move' => (object) [
                    'pgn' => 'd4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::W,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd4',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq d3',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'd7',
                'move' => (object) [
                    'pgn' => 'd5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::B,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd5',
                    ],
                ],
                'fen' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq d6',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'c2',
                'move' => (object) [
                    'pgn' => 'c4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::W,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'c',
                        'next' => 'c4',
                    ],
                ],
                'fen' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq c3',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'c7',
                'move' => (object) [
                    'pgn' => 'c5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::B,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'c',
                        'next' => 'c5',
                    ],
                ],
                'fen' => 'rnbqkbnr/pp2pppp/8/2pp4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq c6',
            ],
        ];

        $this->assertEquals($expected, $board->getHistory());
    }

    /**
     * @test
     */
    public function get_history_in_C60()
    {
        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');
        $C60 = str_replace("\n", "", $C60);

        $board = (new SanPlay($C60))->validate()->getBoard();

        $expected = [
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'e2',
                'move' => (object) [
                    'pgn' => 'e4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::W,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e4',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'e7',
                'move' => (object) [
                    'pgn' => 'e5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::B,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e5',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'g1',
                'move' => (object) [
                    'pgn' => 'Nf3',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Color::W,
                    'id' => Piece::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'f3',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'b8',
                'move' => (object) [
                    'pgn' => 'Nc6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Color::B,
                    'id' => Piece::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'c6',
                    ],
                ],
                'fen' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'f1',
                'move' => (object) [
                    'pgn' => 'Bb5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PIECE,
                    'color' => Color::W,
                    'id' => Piece::B,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'b5',
                    ],
                ],
                'fen' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'f8',
                'move' => (object) [
                    'pgn' => 'Be7',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PIECE,
                    'color' => Color::B,
                    'id' => Piece::B,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'e7',
                    ],
                ],
                'fen' => 'r1bqk1nr/ppppbppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            ],
        ];

        $this->assertEquals($expected, $board->getHistory());
    }

    /**
     * @test
     */
    public function get_history_in_C11()
    {
        $C11 = file_get_contents(self::DATA_FOLDER.'/sample/C11.pgn');
        $C11 = str_replace("\n", "", $C11);

        $board = (new SanPlay($C11))->validate()->getBoard();

        $expected = [
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'e2',
                'move' => (object) [
                    'pgn' => 'e4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::W,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e4',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'e7',
                'move' => (object) [
                    'pgn' => 'e6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::B,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'e',
                        'next' => 'e6',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppp1ppp/4p3/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'd2',
                'move' => (object) [
                    'pgn' => 'd4',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::W,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd4',
                    ],
                ],
                'fen' => 'rnbqkbnr/pppp1ppp/4p3/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq d3',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'd7',
                'move' => (object) [
                    'pgn' => 'd5',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::PAWN,
                    'color' => Color::B,
                    'id' => Piece::P,
                    'sq' => (object) [
                        'current' => 'd',
                        'next' => 'd5',
                    ],
                ],
                'fen' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq d6',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'b1',
                'move' => (object) [
                    'pgn' => 'Nc3',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Color::W,
                    'id' => Piece::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'c3',
                    ],
                ],
                'fen' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq -',
            ],
            (object) [
                'castlingAbility' => 'KQkq',
                'sq' => 'g8',
                'move' => (object) [
                    'pgn' => 'Nf6',
                    'isCapture' => false,
                    'isCheck' => false,
                    'type' => Move::KNIGHT,
                    'color' => Color::B,
                    'id' => Piece::N,
                    'sq' => (object) [
                        'current' => null,
                        'next' => 'f6',
                    ],
                ],
                'fen' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR w KQkq -',
            ],
        ];

        $this->assertEquals($expected, $board->getHistory());
    }

    /*
    |--------------------------------------------------------------------------
    | getPieces()
    |--------------------------------------------------------------------------
    |
    | Gets the pieces by color.
    |
    */

    /**
     * @test
     */
    public function get_pieces_in_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->getBoard();

        $this->assertSame(14, count($board->getPieces(Color::W)));
        $this->assertSame(13, count($board->getPieces(Color::B)));
    }

    /**
     * @test
     */
    public function get_pieces_in_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = (new SanPlay($A74))->validate()->getBoard();

        $this->assertSame(15, count($board->getPieces(Color::W)));
        $this->assertSame(15, count($board->getPieces(Color::B)));
    }

    /**
     * @test
     */
    public function get_pieces_in_B56()
    {
        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SanPlay($B56))->validate()->getBoard();

        $this->assertSame(15, count($board->getPieces(Color::W)));
        $this->assertSame(15, count($board->getPieces(Color::B)));
    }

    /*
    |--------------------------------------------------------------------------
    | validate()
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

    /*
    |--------------------------------------------------------------------------
    | validate()
    |--------------------------------------------------------------------------
    |
    | Illegal moves return false.
    |
    */

    /**
     * @test
     */
    public function init_board_and_pick_a_nonexistent_piece()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'g3', self::$size),
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'f4'));
    }

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
    public function play_w_CASTLE_SHORT()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'O-O'));
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
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'g3', self::$size),
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf4_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf2_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kf2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Re7_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Re7'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_a4_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'a4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxf2()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'g3', self::$size),
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f2', self::$size, RType::CASTLE_SHORT), // rook defended by knight
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kxf2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT()
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
    public function init_board_and_play_w_CASTLE_LONG()
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
    public function init_board_and_play_w_CASTLE_SHORT_with_threats_on_f1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd4', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new B('b', 'a6', self::$size), // bishop threatening f1
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_with_threats_on_f1_g1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new B('b', 'a6', self::$size), // bishop threatening f1
            new K('b', 'e8', self::$size),
            new B('b', 'c5', self::$size), // bishop threatening g1
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_with_threats_on_g1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'c5', self::$size), // bishop threatening g1
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_LONG_with_threats_on_c1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'f4', self::$size), // bishop threatening c1
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_castle_with_threats_on_d1_f1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'e3', self::$size), // knight threatening d1 and f1
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_castle_with_threats_on_b1_f1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'd2', self::$size), // knight threatening b1 and f1
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertTrue($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_LONG_with_threats_on_b1_d1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'c3', self::$size), // knight threatening b1 and d1
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_after_Kf1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kf1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Ke1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_after_Rg1()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'd5', self::$size),
            new P('w', 'e4', self::$size),
            new P('w', 'f3', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Rg1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Rh1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_CASTLE_SHORT_with_threats()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'c2', self::$size),
            new P('w', 'c3', self::$size),
            new P('w', 'd4', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new N('w', 'g1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new B('w', 'a3', self::$size),
            new B('w', 'd3', self::$size),
            new P('b', 'a7', self::$size),
            new P('b', 'b6', self::$size),
            new P('b', 'c7', self::$size),
            new P('b', 'e6', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h6', self::$size),
            new R('b', 'a8', self::$size, RType::CASTLE_LONG),
            new B('b', 'c8', self::$size),
            new Q('b', 'd8', self::$size),
            new K('b', 'e8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new N('b', 'd7', self::$size),
            new N('b', 'f6', self::$size)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertFalse($board->play('b', 'O-O'));
    }

    /*
    |--------------------------------------------------------------------------
    | validate()
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
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new Q('w', 'd1', self::$size),
            new K('w', 'e1', self::$size),
            new B('w', 'f1', self::$size),
            new N('w', 'g1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('w', 'b2', self::$size),
            new P('w', 'c2', self::$size),
            new P('w', 'd2', self::$size),
            new P('w', 'e2', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('b', 'a8', self::$size, RType::CASTLE_LONG),
            new N('b', 'b8', self::$size),
            new B('b', 'c8', self::$size),
            new Q('b', 'd8', self::$size),
            new K('b', 'e8', self::$size),
            new B('b', 'f8', self::$size),
            new N('b', 'g8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'a7', self::$size),
            new P('b', 'b7', self::$size),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQkq';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Rxa6()
    {
        $pieces = [
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new K('b', 'e8', self::$size),
            new B('b', 'a6', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'Q';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Rxa6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_h6()
    {
        $pieces = [
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new K('b', 'e8', self::$size),
            new B('b', 'a6', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'Q';

        $board = new Board($pieces, $castlingAbility);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'h6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_hxg6()
    {
        $pieces = [
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e1', self::$size),
            new P('w', 'g6', self::$size),
            new K('b', 'e8', self::$size),
            new B('b', 'a6', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'Q';

        $board = new Board($pieces, $castlingAbility);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'hxg6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Nxc3()
    {
        $pieces = [
            new N('w', 'b1', self::$size),
            new K('w', 'e1', self::$size),
            new P('w', 'g6', self::$size),
            new K('b', 'e8', self::$size),
            new B('b', 'a6', self::$size),
            new P('b', 'c3', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Nxc3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_CASTLE_SHORT()
    {
        $pieces = [
            new R('w', 'a1', self::$size, RType::CASTLE_LONG),
            new N('w', 'b1', self::$size),
            new B('w', 'c1', self::$size),
            new Q('w', 'd1', self::$size),
            new K('w', 'e1', self::$size),
            new B('w', 'f1', self::$size),
            new N('w', 'g1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('w', 'a2', self::$size),
            new P('w', 'b2', self::$size),
            new P('w', 'c2', self::$size),
            new P('w', 'd2', self::$size),
            new P('w', 'e2', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('b', 'a8', self::$size, RType::CASTLE_LONG),
            new N('b', 'b8', self::$size),
            new B('b', 'c8', self::$size),
            new Q('b', 'd8', self::$size),
            new K('b', 'e8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT),
            new P('b', 'a7', self::$size),
            new P('b', 'b7', self::$size),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = 'KQkq';

        $board = new Board($pieces, $castlingAbility);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke4_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ke4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg3_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kg3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg2_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke2_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ke2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke3_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'f3', self::$size), // in check!
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ke3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg2()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'g3', self::$size),
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f5', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxh2()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'g3', self::$size),
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'h2', self::$size, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kxh2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxf3()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'a3', self::$size),
            new P('w', 'c3', self::$size),
            new R('w', 'e6', self::$size, RType::CASTLE_LONG),
            new K('w', 'g3', self::$size),
            new P('b', 'a6', self::$size),
            new P('b', 'b5', self::$size),
            new P('b', 'c4', self::$size),
            new N('b', 'd3', self::$size),
            new R('b', 'f3', self::$size, RType::CASTLE_SHORT), // rook not defended
            new K('b', 'g5', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kxf3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_exf3()
    {
        $pieces = [
            new P('w', 'e2', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'e4', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size),
            new K('b', 'e8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'f4'));
        $this->assertTrue($board->play('b', 'exf3')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_w_exf6()
    {
        $pieces = [
            new P('w', 'e5', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'e7', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size),
            new K('b', 'e8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);
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
            new P('w', 'e2', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'e7', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g4', self::$size),
            new P('b', 'h7', self::$size),
            new K('b', 'e8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h4'));
        $this->assertTrue($board->play('b', 'gxh3')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_b_hxg3()
    {
        $pieces = [
            new P('w', 'e2', self::$size),
            new P('w', 'f2', self::$size),
            new P('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'e7', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h4', self::$size),
            new K('b', 'e8', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);

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
        $pawn_e5->sqs(); // this creates the en passant property
        $this->assertSame('f6', $pawn_e5->getEnPassantSq());
        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_cxb6()
    {
        $pieces = [
            new P('w', 'a2', self::$size),
            new P('w', 'b2', self::$size),
            new P('w', 'c5', self::$size),
            new R('w', 'd1', self::$size, RType::CASTLE_LONG),
            new K('w', 'e4', self::$size),
            new P('b', 'a7', self::$size),
            new P('b', 'b7', self::$size),
            new P('b', 'c7', self::$size),
            new K('b', 'g6', self::$size),
            new R('b', 'h8', self::$size, RType::CASTLE_LONG),
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);
        $board->setTurn('b');

        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'cxb6')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_q()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=Q'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_q()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->playLan('w', 'h7h8q'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_n()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=N'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_n()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8n');

        $this->assertEquals('N', $board->getPieceBySq('h8')->getId());
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_r()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=R'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_r()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8r');

        $this->assertEquals('R', $board->getPieceBySq('h8')->getId());
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_b()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=B'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_b()
    {
        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8b');

        $this->assertEquals('B', $board->getPieceBySq('h8')->getId());
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_z()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $pieces = [
            new P('w', 'g2', self::$size),
            new P('w', 'h7', self::$size),
            new K('w', 'e1', self::$size),
            new R('w', 'h1', self::$size, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$size),
            new P('b', 'd7', self::$size),
            new P('b', 'e7', self::$size),
            new B('b', 'd6', self::$size),
            new K('b', 'e8', self::$size)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8z');
    }

    /**
     * @test
     */
    public function init_board_and_checkmate_w_Qd7()
    {
        $pieces = [
            new P('w', 'd5', self::$size),
            new Q('w', 'f5', self::$size),
            new K('w', 'g2', self::$size),
            new P('w', 'h2', self::$size),
            new R('w', 'h8', self::$size, RType::CASTLE_LONG),
            new K('b', 'e7', self::$size),
            new P('b', 'f7', self::$size),
            new P('b', 'g7', self::$size),
            new P('b', 'h7', self::$size)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

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
            new K('b', 'h1', self::$size),
            new K('w', 'a8', self::$size),
            new Q('w', 'f2', self::$size),
        ];

        $castlingAbility = '-';

        $board = (new Board($pieces, $castlingAbility))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_pawn_vs_king()
    {
        $pieces = [
            new K('w', 'f6', self::$size),
            new P('w', 'f7', self::$size),
            new K('b', 'f8', self::$size),
        ];

        $castlingAbility = '-';

        $board = (new Board($pieces, $castlingAbility))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_rook_vs_king_and_bishop()
    {
        $pieces = [
            new K('w', 'b6', self::$size),
            new R('w', 'h8', self::$size, RType::CASTLE_LONG),
            new K('b', 'a8', self::$size),
            new B('b', 'b8', self::$size),
        ];

        $castlingAbility = '-';

        $board = (new Board($pieces, $castlingAbility))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_endgame()
    {
        $pieces = [
            new K('w', 'g1', self::$size),
            new Q('w', 'd1', self::$size),
            new R('w', 'a5', self::$size, RType::CASTLE_SHORT),
            new R('w', 'b7', self::$size, RType::CASTLE_LONG),
            new P('w', 'f6', self::$size),
            new P('w', 'g5', self::$size),
            new K('b', 'e6', self::$size),
        ];

        $castlingAbility = '-';

        $board = (new Board($pieces, $castlingAbility))->setTurn('b');

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /*
    |--------------------------------------------------------------------------
    | toArray()
    |--------------------------------------------------------------------------
    |
    | Returns an ASCII array representing a Chess\Variant\Classical\Board object.
    |
    */

    /**
     * @test
     */
    public function to_array_e4_e5()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function to_array_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->getBoard();

        $expected = [
            7 => [ ' r ', ' n ', ' . ', ' q ', ' k ', ' b ', ' . ', ' r ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' . ', ' . ', ' P ', ' . ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' . ', ' K ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function to_array_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = (new SanPlay($A74))->validate()->getBoard();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' . ', ' p ', ' . ', ' . ', ' . ', ' p ', ' b ', ' p ' ],
            5 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' N ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' . ', ' B ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function to_array_C11()
    {
        $C11 = file_get_contents(self::DATA_FOLDER.'/sample/C11.pgn');

        $board = (new SanPlay($C11))->validate()->getBoard();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' . ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' n ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' P ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /*
    |--------------------------------------------------------------------------
    | checkingPieces()
    |--------------------------------------------------------------------------
    |
    | Possible moves.
    |
    */

    /**
     * @test
     */
    public function king_not_attacked()
    {
        $board = (new StrToBoard('5k2/R7/8/4N3/4K3/8/8/8 w - -'))->create();
        $expected = 0;
        $count = count($board->checkingPieces());

        $this->assertSame($expected, $count);
    }

    /**
     * @test
     */
    public function rook_attacking_king()
    {
        $board = (new StrToBoard('R4k2/8/8/8/4K3/8/8/8 w - -'))->create();
        $expected = 1;
        $count = count($board->checkingPieces());

        $this->assertSame($expected, $count);
    }

    /**
     * @test
     */
    public function rook_and_knight_attacking_king()
    {
        $board = (new StrToBoard('R4k2/3N4/8/8/4K3/8/8/8 w - -'))->create();
        $expected = 2;
        $count = count($board->checkingPieces());

        $this->assertSame($expected, $count);
    }

    /*
    |--------------------------------------------------------------------------
    | undo()
    |--------------------------------------------------------------------------
    |
    | Undoes the last move.
    |
    */

    /**
     * @test
     */
    public function undo_e4_e5()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $board = $board->undo();

        $expected = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_b6_Nf3_Bb7_Bc4_Nc6_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'b6');
        $board->play('w', 'Nf3');
        $board->play('b', 'Bb7');
        $board->play('w', 'Bc4');
        $board->play('b', 'Nc6');
        $board->play('w', 'O-O');

        $board = $board->undo();

        $expected = 'r2qkbnr/pbpppppp/1pn5/8/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_b6_Nf3_Bb7_Bc4_Nc6_Ke2()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'b6');
        $board->play('w', 'Nf3');
        $board->play('b', 'Bb7');
        $board->play('w', 'Bc4');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ke2');

        $board = $board->undo();

        $expected = 'r2qkbnr/pbpppppp/1pn5/8/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_e5_Nf3_Nf6_Be2_Be7_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Be2');
        $board->play('b', 'Be7');
        $board->play('w', 'O-O');

        $board = $board->undo();

        $expected = 'rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQK2R w KQkq -';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_e5_Nf3_Nf6_Be2_Be7_O_O_legal()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Be2');
        $board->play('b', 'Be7');
        $board->play('w', 'O-O');

        $board = $board->undo();

        $expected = (object) [
            'color' => 'w',
            'id' => 'K',
            'fen' => (object) [
                'f1' => 'rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQ1K1R b kq -',
                'g1' => 'rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQ1RK1 b kq -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('e1'));
    }

    /**
     * @test
     */
    public function undo_e4_e5_Nf3_Nc6()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $board = $board->undo();
        $board = $board->undo();
        $board = $board->undo();
        $board = $board->undo();

        $board->play('w', 'e4');

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $king = $board->getPieceBySq('e1');

        $expected = [
            'e2',
            'f1',
            'g1',
        ];

        $this->assertEqualsCanonicalizing($expected, $king->sqs());
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7_Kf1()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $this->assertTrue($board->play('w', 'Kf1'));
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7_Ke2()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $this->assertTrue($board->play('w', 'Ke2'));
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $this->assertTrue($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function legal_e4_e5_Nf3_Nf6_Bc4_Be7()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $expected = (object) [
            'color' => 'w',
            'id' => 'K',
            'fen' => (object) [
                'e2' => 'rnbqk2r/ppppbppp/5n2/4p3/2B1P3/5N2/PPPPKPPP/RNBQ3R b kq -',
                'f1' => 'rnbqk2r/ppppbppp/5n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQ1K1R b kq -',
                'g1' => 'rnbqk2r/ppppbppp/5n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('e1'));
    }

    /*
    |--------------------------------------------------------------------------
    | playLan()
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @test
     */
    public function play_lan_w_foo()
    {
        $board = new Board();

        $this->assertFalse($board->playLan('w', 'foo'));
    }

    /**
     * @test
     */
    public function play_lan_w_e2e4()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
    }

    /**
     * @test
     */
    public function play_lan_C00()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'e7e6'));
        $this->assertTrue($board->playLan('w', 'd2d4'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
        $this->assertTrue($board->playLan('w', 'c1e3'));
    }

    /**
     * @test
     */
    public function play_lan_B00()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'b8c6'));
        $this->assertTrue($board->playLan('w', 'g1f3'));
        $this->assertTrue($board->playLan('b', 'd7d6'));
        $this->assertTrue($board->playLan('w', 'f1e2'));
        $this->assertTrue($board->playLan('b', 'c8e6'));
        $this->assertTrue($board->playLan('w', 'e1g1'));
        $this->assertTrue($board->playLan('b', 'd8d7'));
        $this->assertTrue($board->playLan('w', 'h2h3'));
        $this->assertTrue($board->playLan('b', 'e8c8'));
    }

    /**
     * @test
     */
    public function play_lan_e2e4_d7d5_a2a3_d5e4()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
        $this->assertTrue($board->playLan('w', 'a2a3'));
        $this->assertTrue($board->playLan('b', 'd5e4'));
    }

    /**
     * @test
     */
    public function play_lan_e2e4_e7e5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertFalse($board->playLan('w', 'e7e5'));
    }

    /**
     * @test
     */
    public function play_lan_b1a3_d7d5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'b1a3'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
    }

    /**
     * @test
     */
    public function play_d3_d6___Nd7_Nf3()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'e6'));
        $this->assertTrue($board->play('w', 'Nd2'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertTrue($board->play('w', 'Nd2f3'));

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' n ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' p ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' P ', ' P ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function play_lan_d2d3_d7d6___b8d7_d2f3()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'd2d3'));
        $this->assertTrue($board->playLan('b', 'd7d6'));
        $this->assertTrue($board->playLan('w', 'e2e3'));
        $this->assertTrue($board->playLan('b', 'e7e6'));
        $this->assertTrue($board->playLan('w', 'b1d2'));
        $this->assertTrue($board->playLan('b', 'b8d7'));
        $this->assertTrue($board->playLan('w', 'd2f3'));

        $expected = [
            7 => [ ' r ', ' . ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' n ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' p ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' P ', ' P ', ' N ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function play_d4_d5___axb5_cxb5()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'c4'));
        $this->assertTrue($board->play('b', 'dxc4'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'a4'));
        $this->assertTrue($board->play('b', 'c6'));
        $this->assertTrue($board->play('w', 'axb5'));
        $this->assertTrue($board->play('b', 'cxb5'));
    }

    /**
     * @test
     */
    public function play_lan_d2d4_d7d5___a4b5_c6b5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'd2d4'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
        $this->assertTrue($board->playLan('w', 'c2c4'));
        $this->assertTrue($board->playLan('b', 'd5c4'));
        $this->assertTrue($board->playLan('w', 'g1f3'));
        $this->assertTrue($board->playLan('b', 'b7b5'));
        $this->assertTrue($board->playLan('w', 'a2a4'));
        $this->assertTrue($board->playLan('b', 'c7c6'));
        $this->assertTrue($board->playLan('w', 'a4b5'));
        $this->assertTrue($board->playLan('b', 'c6b5'));
    }

    /**
     * @test
     */
    public function is_fivefold_repetition()
    {
        $board = new Board();

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');

        $this->assertTrue($board->isFivefoldRepetition());
    }

    /**
     * @test
     */
    public function play_lan_e2e4_f7f5_d1h5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'f7f5'));
        $this->assertTrue($board->playLan('w', 'd1h5'));

        $expected = '1.e4 f5 2.Qh5+';

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function play_exe4_exe5()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'exe4'));
        $this->assertFalse($board->play('b', 'exe5'));
    }

    /**
     * @test
     */
    public function play_h4_a5_h5_g5()
    {
        $board = new Board();

        $board->play('w', 'h4');
        $board->play('b', 'a5');
        $board->play('w', 'h5');
        $board->play('b', 'g5');

        $expected = (object) [
            'color' => 'w',
            'id' => 'P',
            'fen' => (object) [
                'h6' => 'rnbqkbnr/1ppppp1p/7P/p5p1/8/8/PPPPPPP1/RNBQKBNR b KQkq -',
                'g6' => 'rnbqkbnr/1ppppp1p/6P1/p7/8/8/PPPPPPP1/RNBQKBNR b KQkq -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('h5'));
    }

    /**
     * @test
     */
    public function play_lan_h2h4_a7a5_h4h5_g7g5()
    {
        $board = new Board();

        $board->playLan('w', 'h2h4');
        $board->playLan('b', 'a7a5');
        $board->playLan('w', 'h4h5');
        $board->playLan('b', 'g7g5');

        $expected = (object) [
            'color' => 'w',
            'id' => 'P',
            'fen' => (object) [
                'h6' => 'rnbqkbnr/1ppppp1p/7P/p5p1/8/8/PPPPPPP1/RNBQKBNR b KQkq -',
                'g6' => 'rnbqkbnr/1ppppp1p/6P1/p7/8/8/PPPPPPP1/RNBQKBNR b KQkq -',
            ],
        ];

        $this->assertEquals($expected, $board->legal('h5'));
    }

    /**
     * @test
     */
    public function legal_a1()
    {
        $board = new Board();

        $expected = (object) [
            'color' => 'w',
            'id' => 'R',
            'fen' => (object) [],
        ];

        $this->assertEquals($expected, $board->legal('a1'));
    }
}
