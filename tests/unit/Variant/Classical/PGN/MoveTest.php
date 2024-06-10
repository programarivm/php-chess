<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN;

use Chess\Piece\K;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Rule\CastlingRule;

class MoveTest extends AbstractUnitTestCase
{
    static private $color;
    static private $castlingRule;
    static private $move;

    public static function setUpBeforeClass(): void
    {
        self::$color = new Color();
        self::$castlingRule = new CastlingRule();
        self::$move = new Move();
    }

    /**
     * @test
     */
    public function Ua5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'Ua5', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function foo5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'foo5', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function cb3b7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'cb3b7', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'a-a', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function CASTLE_LONG_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'c-c-c', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function a_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'a', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function three_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 3, self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function K3_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'K3', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function Fxa7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'Fxa7', self::$castlingRule, self::$color);
    }

    /**
     * @test
     */
    public function Bg5()
    {
        $move = 'Bg5';
        $expected = [
            'pgn' => 'Bg5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'w',
            'id' => Piece::B,
            'sq' => [
                'current' => '',
                'next' =>'g5'
            ]
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Ra5()
    {
        $move = 'Ra5';
        $expected = [
            'pgn' => 'Ra5',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::R,
            'sq' => [
                'current' => '',
                'next' => 'a5'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Qbb7()
    {
        $move = 'Qbb7';
        $expected = [
            'pgn' => 'Qbb7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => [
                'current' => 'b',
                'next' => 'b7'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Ndb4()
    {
        $move = 'Ndb4';
        $expected = [
            'pgn' => 'Ndb4',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::KNIGHT),
            'color' => 'b',
            'id' => Piece::N,
            'sq' => [
                'current' => 'd',
                'next' => 'b4'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Kg7()
    {
        $move = 'Kg7';
        $expected = [
            'pgn' => 'Kg7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::KING),
            'color' => 'w',
            'id' => Piece::K,
            'sq' => [
                'current' => '',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Qh8g7()
    {
        $move = 'Qh8g7';
        $expected = [
            'pgn' => 'Qh8g7',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => [
                'current' => 'h8',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
     * @test
     */
    public function c3()
    {
        $move = 'c3';
        $expected = [
            'pgn' => 'c3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => Piece::P,
            'sq' => [
                'current' => 'c',
                'next' => 'c3'
            ]
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function h4()
    {
        $move = 'h3';
        $expected = [
            'pgn' => 'h3',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => Piece::P,
            'sq' => [
                'current' => 'h',
                'next' => 'h3'
            ]
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT()
    {
        $move = 'O-O';
        $expected = [
            'pgn' => 'O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::CASTLE_SHORT),
            'color' => 'w',
            'id' => 'K',
            'sq' => self::$castlingRule->getRule()['w'][Piece::K][Castle::SHORT]['sq']
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function CASTLE_LONG()
    {
        $move = 'O-O-O';
        $expected = [
            'pgn' => 'O-O-O',
            'isCapture' => false,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::CASTLE_LONG),
            'color' => 'w',
            'id' => 'K',
            'sq' => self::$castlingRule->getRule()['w'][Piece::K][Castle::LONG]['sq']
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
     * @test
     */
    public function fxg5()
    {
        $move = 'fxg5';
        $expected = [
            'pgn' => 'fxg5',
            'isCapture' => true,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PAWN_CAPTURES),
            'color' => 'b',
            'id' => Piece::P,
            'sq' => [
                'current' => 'f',
                'next' => 'g5'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Nxe4()
    {
        $move = 'Nxe4';
        $expected = [
            'pgn' => 'Nxe4',
            'isCapture' => true,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::KNIGHT_CAPTURES),
            'color' => 'b',
            'id' => Piece::N,
            'sq' => [
                'current' => '',
                'next' => 'e4'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }

    /**
	 * @test
	 */
    public function Q7xg7()
    {
        $move = 'Q7xg7';
        $expected = [
            'pgn' => 'Q7xg7',
            'isCapture' => true,
            'isCheck' => false,
            'type' => self::$move->case(MOVE::PIECE_CAPTURES),
            'color' => 'b',
            'id' => Piece::Q,
            'sq' => [
                'current' => '7',
                'next' => 'g7'
            ]
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$castlingRule, self::$color), $expected);
    }
}
