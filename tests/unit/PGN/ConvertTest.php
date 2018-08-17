<?php

namespace PGNChess\Tests\Unit\PGN;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\Square\Castling;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;
use PHPUnit\Framework\TestCase;

class ConvertTest extends TestCase
{
    /**
     * @test
     */
    public function Ua5_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::WHITE, 'Ua5');
    }

    /**
	 * @test
	 */
    public function foo5_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::BLACK, 'foo5');
    }

    /**
	 * @test
	 */
    public function cb3b7_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::WHITE, 'cb3b7');
    }

    /**
	 * @test
	 */
    public function O_O_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::BLACK, 'a-a');
    }

    /**
	 * @test
	 */
    public function O_O_O_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::WHITE, 'c-c-c');
    }

    /**
	 * @test
	 */
    public function a_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::BLACK, 'a');
    }

    /**
	 * @test
	 */
    public function three_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::WHITE, 3);
    }

    /**
	 * @test
	 */
    public function K3_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::BLACK, 'K3');
    }

    /**
	 * @test
	 */
    public function Fxa7_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);
        Convert::toObject(Symbol::WHITE, 'Fxa7');
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
            'color' => Symbol::WHITE,
            'identity' => Symbol::BISHOP,
            'position' => (object) [
                'current' => null,
                'next' =>'g5'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::ROOK,
            'position' => (object) [
                'current' => null,
                'next' => 'a5'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::QUEEN,
            'position' => (object) [
                'current' => Symbol::BLACK,
                'next' => 'b7'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::KNIGHT,
            'position' => (object) [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
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
            'color' => Symbol::WHITE,
            'identity' => Symbol::KING,
            'position' => (object) [
                'current' => null,
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::QUEEN,
            'position' => (object) [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
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
            'color' => Symbol::WHITE,
            'identity' => Symbol::PAWN,
            'position' => (object) [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
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
            'color' => Symbol::WHITE,
            'identity' => Symbol::PAWN,
            'position' => (object) [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    /**
     * @test
     */
    public function O_O()
    {
        $move = 'O-O';
        $example = (object) [
            'pgn' => 'O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING_CASTLING_SHORT,
            'color' => Symbol::WHITE,
            'identity' => 'K',
            'position' => Castling::info(Symbol::WHITE)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position
        ];

        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
    }

    /**
	 * @test
	 */
    public function O_O_O()
    {
        $move = 'O-O-O';
        $example = (object) [
            'pgn' => 'O-O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => Move::KING_CASTLING_LONG,
            'color' => Symbol::WHITE,
            'identity' => 'K',
            'position' => Castling::info(Symbol::WHITE)->{Symbol::KING}->{Symbol::CASTLING_LONG}->position
        ];

        $this->assertEquals(Convert::toObject(Symbol::WHITE, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::PAWN,
            'position' => (object) [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::KNIGHT,
            'position' => (object) [
                'current' => null,
                'next' => 'e4'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
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
            'color' => Symbol::BLACK,
            'identity' => Symbol::QUEEN,
            'position' => (object) [
                'current' => '7',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(Convert::toObject(Symbol::BLACK, $move), $example);
    }
}
