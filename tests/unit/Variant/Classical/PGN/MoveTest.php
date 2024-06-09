<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Piece\K;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Rule\CastlingRule;

class MoveTest extends AbstractUnitTestCase
{
    static private $castlingRule;
    static private $move;

    public static function setUpBeforeClass(): void
    {
        self::$castlingRule = new CastlingRule();
        self::$move = new Move();
    }

    /**
     * @test
     */
    public function Ua5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('w', 'Ua5', self::$castlingRule);
    }

    /**
     * @test
     */
    public function foo5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('b', 'foo5', self::$castlingRule);
    }

    /**
     * @test
     */
    public function cb3b7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('w', 'cb3b7', self::$castlingRule);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('b', 'a-a', self::$castlingRule);
    }

    /**
     * @test
     */
    public function CASTLE_LONG_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('w', 'c-c-c', self::$castlingRule);
    }

    /**
     * @test
     */
    public function a_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('b', 'a', self::$castlingRule);
    }

    /**
     * @test
     */
    public function three_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('w', 3, self::$castlingRule);
    }

    /**
     * @test
     */
    public function K3_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('b', 'K3', self::$castlingRule);
    }

    /**
     * @test
     */
    public function Fxa7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toObj('w', 'Fxa7', self::$castlingRule);
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
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'w',
            'id' => Piece::B,
            'sq' => (object) [
                'current' => '',
                'next' =>'g5'
            ]
        ];

        $this->assertEquals(self::$move->toObj('w', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::R,
            'sq' => (object) [
                'current' => '',
                'next' => 'a5'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => (object) [
                'current' => 'b',
                'next' => 'b7'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::KNIGHT),
            'color' => 'b',
            'id' => Piece::N,
            'sq' => (object) [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::KING),
            'color' => 'w',
            'id' => Piece::K,
            'sq' => (object) [
                'current' => '',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(self::$move->toObj('w', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => (object) [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => Piece::P,
            'sq' => (object) [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];

        $this->assertEquals(self::$move->toObj('w', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => Piece::P,
            'sq' => (object) [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];

        $this->assertEquals(self::$move->toObj('w', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::CASTLE_SHORT),
            'color' => 'w',
            'id' => 'K',
            'sq' => (object) self::$castlingRule->getRule()['w'][Piece::K][Castle::SHORT]['sq']
        ];

        $this->assertEquals(self::$move->toObj('w', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::CASTLE_LONG),
            'color' => 'w',
            'id' => 'K',
            'sq' => (object) self::$castlingRule->getRule()['w'][Piece::K][Castle::LONG]['sq']
        ];

        $this->assertEquals(self::$move->toObj('w', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PAWN_CAPTURES),
            'color' => 'b',
            'id' => Piece::P,
            'sq' => (object) [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::KNIGHT_CAPTURES),
            'color' => 'b',
            'id' => Piece::N,
            'sq' => (object) [
                'current' => '',
                'next' => 'e4'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
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
            'type' => self::$move->case(MOVE::PIECE_CAPTURES),
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => (object) [
                'current' => '7',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(self::$move->toObj('b', $move, self::$castlingRule), $example);
    }
}
