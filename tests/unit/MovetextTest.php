<?php

namespace Chess\Tests\Unit\Variant\Classical\PGN;

use Chess\Movetext;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;

class MovetextTest extends AbstractUnitTestCase
{
    static private $move;

    public static function setUpBeforeClass(): void
    {
        self::$move = new ClassicalPgnMove();
    }

    public static $validData = [
        '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
        '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7',
        '1.e4 c5 2.Nf3 Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
        '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5',
        '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6',
        '1...Bg7 2.e4',
        '1...Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6',
        '2...c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6',
    ];

    /**
     * @test
     */
    public function get_movetext()
    {
        $text = '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5';

        $expected = [ 'd4', 'Nf6', 'Nf3', 'e6', 'c4', 'Bb4+', 'Nbd2', 'O-O', 'a3', 'Be7', 'e4', 'd6', 'Bd3', 'c5' ];

        $this->assertEquals($expected, (new Movetext(self::$move, $text))->getMoves());
    }

    /**
     * @dataProvider sequenceData
     * @test
     */
    public function sequence($text, $expected)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->sequence());
    }

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Movetext(self::$move, 'foo'))->validate();
    }

    /**
     * @dataProvider validData
     * @test
     */
    public function valid($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    /**
     * @dataProvider wrongNumbersData
     * @test
     */
    public function wrong_numbers($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    /**
     * @dataProvider invalidMovesData
     * @test
     */
    public function invalid_moves($text)
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Movetext(self::$move, $text))->validate();
    }

    /**
     * @dataProvider curlyBracesFilteredData
     * @test
     */
    public function curly_braces_filtered($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    /**
     * @dataProvider parenthesesFilteredData
     * @test
     */
    public function parentheses_filtered($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    /**
     * @dataProvider tooManySpacesFilteredData
     * @test
     */
    public function too_many_spaces_filtered($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    /**
     * @dataProvider fideFilteredData
     * @test
     */
    public function fide_filtered($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    /**
     * @dataProvider withResultData
     * @test
     */
    public function with_result_filtered($expected, $text)
    {
        $this->assertSame($expected, (new Movetext(self::$move, $text))->validate());
    }

    public function validData()
    {
        return [
            [ self::$validData[0], self::$validData[0] ],
            [ self::$validData[1], self::$validData[1] ],
            [ self::$validData[2], self::$validData[2] ],
            [ self::$validData[3], self::$validData[3] ],
            [ self::$validData[4], self::$validData[4] ],
            [ self::$validData[5], self::$validData[5] ],
            [ self::$validData[6], self::$validData[6] ],
            [ '1...c5 2.g3 b6 3.Bg2 Bb7 4.O-O e6 5.Nc3 a6 6.d4 cxd4 7.Qxd4 d6', self::$validData[7]],
        ];
    }

    public function wrongNumbersData()
    {
        return [
            [ self::$validData[0], '7.d4 Nf6 6.Nf3 e6 5.c4 Bb4+ 4.Nbd2 O-O 3.a3 Be7 2.e4 d6 1.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 3.e5 Nd5 2.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 7.Be2 Bf5 6.c3 Nd7' ],
            [ self::$validData[2], '7.e4 c5 7.Nf3 Nc6 7.d4 cxd4 1.Nxd4 Nf6 1.Nc3 e5 1.Ndb5 d6 1.Bg5 a6 1.Na3' ],
            [ self::$validData[3], '108.d4 Nf6 107.c4 e6 3.Nc3 Bb4 106.e3 O-O 5.a3 Bxc3+ 125.bxc3 b6 230.Bd3 Bb7 1024.f3 c5' ],
            [ self::$validData[4], '8.Nf3 Nf6 7.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6' ],
        ];
    }

    public function invalidMovesData()
    {
        return [
            [ '1.d4 Nf6 2.Nf3 FOO 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.BAR c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ '1.e4 c5 2.Nf3 Nc6 3.FOO cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 BAR' ],
            [ '1.Nf3 Nf6 2.c4 c5 3.g3 BAR 4.Bg2 FOO 5.O-O e6 6.FOOBAR 7.d4 cxd4 8.Qxd4 d6' ],
        ];
    }

    public function curlyBracesFilteredData()
    {
        return [
            [ self::$validData[0], '{This is foo} 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 {This is foo} 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4 c5 2.Nf3 {This is foo} Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 {This is foo}' ],
            [ self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 {This is foo} d6' ],
        ];
    }

    public function parenthesesFilteredData()
    {
        return [
            [ self::$validData[0], '(This is foo) 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 (This is foo) 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4 c5 2.Nf3 (This is foo) Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 (This is foo)' ],
            [ self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 (This is foo) d6' ],
        ];
    }

    public function tooManySpacesFilteredData()
    {
        return [
            [ self::$validData[0], '1  .  d4    Nf6 2.Nf3 e6 3.c4    Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 2.   e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.   Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4  c5   2.Nf3   Nc6 3.d4     cxd4 4   .  Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+    6.bxc3 b6   7.Bd3   Bb7   8.f3   c5' ],
            [ self::$validData[4], '1.Nf3   Nf6 2.c4   c5  3.g3  b6  4.Bg2  Bb7  5.O-O e6 6.Nc3 a6 7.d4  cxd4  8.Qxd4  d6' ],
        ];
    }

    public function fideFilteredData()
    {
        return [
            [ self::$validData[0], '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 0-0 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4 c5 2.Nf3 Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 0-0 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5' ],
            [ self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.0-0 e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6' ],
        ];
    }

    public function withResultData()
    {
        return [
            [ self::$validData[0], self::$validData[0] . ' 1-0' ],
            [ self::$validData[1], self::$validData[1] . ' 0-1' ],
            [ self::$validData[2], self::$validData[2] . ' 1/2-1/2' ],
            [ self::$validData[3], self::$validData[3] . ' *' ],
            [ self::$validData[4], self::$validData[4] . ' 1–0' ],
            [ self::$validData[0], self::$validData[0] . ' 0–1' ],
            [ self::$validData[1], self::$validData[1] . ' 1/2–1/2' ],
            [ self::$validData[2], self::$validData[2] . ' ½–½' ],
        ];
    }

    public function sequenceData()
    {
        return [
            [
                '1.d4 Nf6 2.Nf3 e6 3.c4', [
                    '1.d4 Nf6',
                    '1.d4 Nf6 2.Nf3 e6',
                ],
            ],
            [
                '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+', [
                    '1.d4 Nf6',
                    '1.d4 Nf6 2.Nf3 e6',
                    '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+',
                ],
            ],
            [
                '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5', [
                    '1.d4 Nf6',
                    '1.d4 Nf6 2.Nf3 e6',
                    '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+',
                    '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O',
                    '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7',
                    '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6',
                    '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
                ],
            ],
            [
                '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7', [
                    '1.e4 Nf6',
                    '1.e4 Nf6 2.e5 Nd5',
                    '1.e4 Nf6 2.e5 Nd5 3.d4 d6',
                    '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5',
                    '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6',
                    '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5',
                    '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7',
                ],
            ],
        ];
    }
}
