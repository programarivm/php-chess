<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\PGN\Move;
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
        $expected = [
            'pgn' => 'Bg5',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'w',
            'id' => 'B',
            'from' => '',
            'to' => 'g5',
        ];

        $move = self::$move->toArray('w', 'Bg5', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Ra5()
    {
        $expected = [
            'pgn' => 'Ra5',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => 'R',
            'from' => '',
            'to' => 'a5',
        ];

        $move = self::$move->toArray('b', 'Ra5', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Qbb7()
    {
        $expected = [
            'pgn' => 'Qbb7',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => 'Q',
            'from' => 'b',
            'to' => 'b7',
        ];

        $move = self::$move->toArray('b', 'Qbb7', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Ndb4()
    {
        $expected = [
            'pgn' => 'Ndb4',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => 'N',
            'from' => 'd',
            'to' => 'b4',
        ];

        $move = self::$move->toArray('b', 'Ndb4', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Kg7()
    {
        $expected = [
            'pgn' => 'Kg7',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'w',
            'id' => 'K',
            'from' => '',
            'to' => 'g7',
        ];

        $move = self::$move->toArray('w', 'Kg7', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Qh8g7()
    {
        $expected = [
            'pgn' => 'Qh8g7',
            'case' => self::$move->case(MOVE::PIECE),
            'color' => 'b',
            'id' => 'Q',
            'from' => 'h8',
            'to' => 'g7',
        ];

        $move = self::$move->toArray('b', 'Qh8g7', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
     * @test
     */
    public function c3()
    {
        $expected = [
            'pgn' => 'c3',
            'case' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => 'P',
            'from' => 'c',
            'to' => 'c3',
        ];

        $move = self::$move->toArray('w', 'c3', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function h4()
    {
        $expected = [
            'pgn' => 'h3',
            'case' => self::$move->case(MOVE::PAWN),
            'color' => 'w',
            'id' => 'P',
            'from' => 'h',
            'to' => 'h3',
        ];

        $move = self::$move->toArray('w', 'h3', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
     * @test
     */
    public function CASTLE_SHORT()
    {
        $expected = [
            'pgn' => 'O-O',
            'case' => self::$move->case(MOVE::CASTLE_SHORT),
            'color' => 'w',
            'id' => 'K',
            'from' => 'e1',
            'to' => 'g1',
        ];

        $move = self::$move->toArray('w', 'O-O', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function CASTLE_LONG()
    {
        $expected = [
            'pgn' => 'O-O-O',
            'case' => self::$move->case(MOVE::CASTLE_LONG),
            'color' => 'w',
            'id' => 'K',
            'from' => 'e1',
            'to' => 'c1',
        ];

        $move = self::$move->toArray('w', 'O-O-O', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
     * @test
     */
    public function fxg5()
    {
        $expected = [
            'pgn' => 'fxg5',
            'case' => self::$move->case(MOVE::PAWN_CAPTURES),
            'color' => 'b',
            'id' => 'P',
            'from' => 'f',
            'to' => 'g5',
        ];

        $move = self::$move->toArray('b', 'fxg5', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Nxe4()
    {
        $expected = [
            'pgn' => 'Nxe4',
            'case' => self::$move->case(MOVE::PIECE_CAPTURES),
            'color' => 'b',
            'id' => 'N',
            'from' => '',
            'to' => 'e4',
        ];

        $move = self::$move->toArray('b', 'Nxe4', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }

    /**
	 * @test
	 */
    public function Q7xg7()
    {
        $expected = [
            'pgn' => 'Q7xg7',
            'case' => self::$move->case(MOVE::PIECE_CAPTURES),
            'color' => 'b',
            'id' => 'Q',
            'from' => '7',
            'to' => 'g7',
        ];

        $move = self::$move->toArray('b', 'Q7xg7', self::$square, self::$castlingRule);

        $this->assertEquals($expected, $move);
    }
}
