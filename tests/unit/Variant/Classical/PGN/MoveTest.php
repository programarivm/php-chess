<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN;

use Chess\Piece\K;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class MoveTest extends AbstractUnitTestCase
{
    static private Square $square;
    static private CastlingRule $castlingRule;
    static private Move $move;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
        self::$castlingRule = new CastlingRule();
        self::$move = new Move();
    }

    /**
     * @test
     */
    public function Ua5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'Ua5', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function foo5_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'foo5', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function cb3b7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'cb3b7', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'a-a', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function CASTLE_LONG_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'c-c-c', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function a_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'a', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function three_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 3, self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function K3_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('b', 'K3', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function Fxa7_throws_exception()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        self::$move->toArray('w', 'Fxa7', self::$square, self::$castlingRule);
    }

    /**
     * @test
     */
    public function Bg5()
    {
        $move = 'Bg5';
        $expected = [
            'pgn' => 'Bg5',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'w',
            'id' => Piece::B,
            'from' => '',
            'to' => 'g5',
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Ra5()
    {
        $move = 'Ra5';
        $expected = [
            'pgn' => 'Ra5',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::R,
            'from' => '',
            'to' => 'a5',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Qbb7()
    {
        $move = 'Qbb7';
        $expected = [
            'pgn' => 'Qbb7',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::Q,
            'from' => 'b',
            'to' => 'b7',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Ndb4()
    {
        $move = 'Ndb4';
        $expected = [
            'pgn' => 'Ndb4',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::N,
            'from' => 'd',
            'to' => 'b4',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Kg7()
    {
        $move = 'Kg7';
        $expected = [
            'pgn' => 'Kg7',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'w',
            'id' => Piece::K,
            'from' => '',
            'to' => 'g7',
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Qh8g7()
    {
        $move = 'Qh8g7';
        $expected = [
            'pgn' => 'Qh8g7',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => Piece::Q,
            'from' => 'h8',
            'to' => 'g7',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
     * @test
     */
    public function c3()
    {
        $move = 'c3';
        $expected = [
            'pgn' => 'c3',
            'case' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => Piece::P,
            'from' => 'c',
            'to' => 'c3',
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function h4()
    {
        $move = 'h3';
        $expected = [
            'pgn' => 'h3',
            'case' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => Piece::P,
            'from' => 'h',
            'to' => 'h3',
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT()
    {
        $move = 'O-O';
        $expected = [
            'pgn' => 'O-O',
            'case' => self::$move->case(MOVE::CASTLE_SHORT),
            'color' => 'w',
            'id' => 'K',
            'from' => self::$castlingRule->rule['w'][Piece::K][Castle::SHORT]['from'],
            'to' => self::$castlingRule->rule['w'][Piece::K][Castle::SHORT]['to'],
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function CASTLE_LONG()
    {
        $move = 'O-O-O';
        $expected = [
            'pgn' => 'O-O-O',
            'case' => self::$move->case(MOVE::CASTLE_LONG),
            'color' => 'w',
            'id' => 'K',
            'from' => self::$castlingRule->rule['w'][Piece::K][Castle::LONG]['from'],
            'to' => self::$castlingRule->rule['w'][Piece::K][Castle::LONG]['to'],
        ];

        $this->assertEquals(self::$move->toArray('w', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
     * @test
     */
    public function fxg5()
    {
        $move = 'fxg5';
        $expected = [
            'pgn' => 'fxg5',
            'case' => self::$move->case(MOVE::PAWN_CAPTURES),
            'color' => 'b',
            'id' => Piece::P,
            'from' => 'f',
            'to' => 'g5',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Nxe4()
    {
        $move = 'Nxe4';
        $expected = [
            'pgn' => 'Nxe4',
            'case' => self::$move->case(MOVE::PIECE_CAPTURES),
            'color' => 'b',
            'id' => Piece::N,
            'from' => '',
            'to' => 'e4',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }

    /**
	 * @test
	 */
    public function Q7xg7()
    {
        $move = 'Q7xg7';
        $expected = [
            'pgn' => 'Q7xg7',
            'case' => self::$move->case(MOVE::PIECE_CAPTURES),
            'color' => 'b',
            'id' => Piece::Q,
            'from' => '7',
            'to' => 'g7',
        ];

        $this->assertEquals(self::$move->toArray('b', $move, self::$square, self::$castlingRule), $expected);
    }
}
