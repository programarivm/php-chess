<?php

namespace Chess\Tests\Unit\PGN;

use Chess\CastlingRule;
use Chess\PGN\Move;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Piece;
use Chess\Tests\AbstractUnitTestCase;

class MoveTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function Ua5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('w', 'Ua5');
    }

    /**
     * @test
     */
    public function foo5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('b', 'foo5');
    }

    /**
     * @test
     */
    public function cb3b7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('w', 'cb3b7');
    }

    /**
     * @test
     */
    public function CASTLE_SHORT_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('b', 'a-a');
    }

    /**
     * @test
     */
    public function CASTLE_LONG_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('w', 'c-c-c');
    }

    /**
     * @test
     */
    public function a_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('b', 'a');
    }

    /**
     * @test
     */
    public function three_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('w', 3);
    }

    /**
     * @test
     */
    public function K3_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('b', 'K3');
    }

    /**
     * @test
     */
    public function Fxa7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        Move::toObj('w', 'Fxa7');
    }

    /**
     * @test
     */
    public function Bg5()
    {
        $move = 'Bg5';
        $example = (object) [
            'pgn' => 'Bg5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'w',
            'id' => Piece::B,
            'sq' => (object) [
                'current' => null,
                'next' =>'g5'
            ]
        ];

        $this->assertEquals(Move::toObj('w', $move), $example);
    }

    /**
	 * @test
	 */
    public function Ra5()
    {
        $move = 'Ra5';
        $example = (object) [
            'pgn' => 'Ra5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'b',
            'id' => Piece::R,
            'sq' => (object) [
                'current' => null,
                'next' => 'a5'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }

    /**
	 * @test
	 */
    public function Qbb7()
    {
        $move = 'Qbb7';
        $example = (object) [
            'pgn' => 'Qbb7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => (object) [
                'current' => 'b',
                'next' => 'b7'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }

    /**
	 * @test
	 */
    public function Ndb4()
    {
        $move = 'Ndb4';
        $example = (object) [
            'pgn' => 'Ndb4',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KNIGHT,
            'color' => 'b',
            'id' => Piece::N,
            'sq' => (object) [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }

    /**
	 * @test
	 */
    public function Kg7()
    {
        $move = 'Kg7';
        $example = (object) [
            'pgn' => 'Kg7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING,
            'color' => 'w',
            'id' => Piece::K,
            'sq' => (object) [
                'current' => null,
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(Move::toObj('w', $move), $example);
    }

    /**
	 * @test
	 */
    public function Qh8g7()
    {
        $move = 'Qh8g7';
        $example = (object) [
            'pgn' => 'Qh8g7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PIECE,
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => (object) [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }

    /**
     * @test
     */
    public function c3()
    {
        $move = 'c3';
        $example = (object) [
            'pgn' => 'c3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PAWN,
            'color' => 'w',
            'id' => Piece::P,
            'sq' => (object) [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];

        $this->assertEquals(Move::toObj('w', $move), $example);
    }

    /**
	 * @test
	 */
    public function h4()
    {
        $move = 'h3';
        $example = (object) [
            'pgn' => 'h3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::PAWN,
            'color' => 'w',
            'id' => Piece::P,
            'sq' => (object) [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];

        $this->assertEquals(Move::toObj('w', $move), $example);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT()
    {
        $move = 'O-O';
        $example = (object) [
            'pgn' => 'O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::CASTLE_SHORT,
            'color' => 'w',
            'id' => 'K',
            'sq' => (object) CastlingRule::color('w')[Piece::K][Castle::SHORT]['sq']
        ];

        $this->assertEquals(Move::toObj('w', $move), $example);
    }

    /**
	 * @test
	 */
    public function CASTLE_LONG()
    {
        $move = 'O-O-O';
        $example = (object) [
            'pgn' => 'O-O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::CASTLE_LONG,
            'color' => 'w',
            'id' => 'K',
            'sq' => (object) CastlingRule::color('w')[Piece::K][Castle::LONG]['sq']
        ];

        $this->assertEquals(Move::toObj('w', $move), $example);
    }

    /**
     * @test
     */
    public function fxg5()
    {
        $move = 'fxg5';
        $example = (object) [
            'pgn' => 'fxg5',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::PAWN_CAPTURES,
            'color' => 'b',
            'id' => Piece::P,
            'sq' => (object) [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }

    /**
	 * @test
	 */
    public function Nxe4()
    {
        $move = 'Nxe4';
        $example = (object) [
            'pgn' => 'Nxe4',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::KNIGHT_CAPTURES,
            'color' => 'b',
            'id' => Piece::N,
            'sq' => (object) [
                'current' => null,
                'next' => 'e4'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }

    /**
	 * @test
	 */
    public function Q7xg7()
    {
        $move = 'Q7xg7';
        $example = (object) [
            'pgn' => 'Q7xg7',
            'isCapture' => true,
            'isCheck' => false,
            'type' => Move::PIECE_CAPTURES,
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => (object) [
                'current' => '7',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(Move::toObj('b', $move), $example);
    }
}
