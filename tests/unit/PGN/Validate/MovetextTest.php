<?php

namespace Chess\Tests\Unit\PGN\Validate;

use Chess\PGN\Validate;
use Chess\Tests\AbstractUnitTestCase;

class MovetextTest extends AbstractUnitTestCase
{
    public static $validData = [
        '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
        '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7',
        '1.e4 c5 2.Nf3 Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
        '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5',
        '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6',
    ];

    /**
     * @dataProvider validData
     * @test
     */
    public function valid($movetext)
    {
        $this->assertSame($movetext, Validate::movetext($movetext));
    }

    /**
     * @dataProvider validFIDEData
     * @test
     */
    public function validFIDE($movetext)
    {
        $this->assertSame($movetext, Validate::movetext($movetext));
    }

    /**
     * @dataProvider commentsRemovedData
     * @test
     */
    public function comments_removed($expected, $movetext)
    {
        $this->assertSame($expected, Validate::movetext($movetext));
    }

    /**
     * @dataProvider tooManySpacesData
     * @test
     */
    public function too_many_spaces($expected, $movetext)
    {
        $this->assertSame($expected, Validate::movetext($movetext));
    }

    /**
     * @dataProvider wrongNumbersData
     * @test
     */
    public function wrong_numbers($movetext)
    {
        $this->assertFalse(Validate::movetext($movetext));
    }

    /**
     * @dataProvider invalidMovesData
     * @test
     */
    public function invalid_moves($movetext)
    {
        $this->assertFalse(Validate::movetext($movetext));
    }

    public function validData()
    {
        return [
            self::$validData,
        ];
    }

    public function commentsRemovedData()
    {
        return [
            [
                self::$validData[0], '{This is foo} 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
                self::$validData[1], '1.e4 Nf6 {This is foo} 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7',
                self::$validData[2], '1.e4 c5 2.Nf3 {This is foo} Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
                self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 {This is foo}',
                self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 {This is foo} d6',
            ],
        ];
    }

    public function tooManySpacesData()
    {
        return [
            [
                self::$validData[0], '1  .  d4    Nf6 2.Nf3 e6 3.c4    Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
                self::$validData[1], '1.e4 Nf6 2.   e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.   Be2 Bf5 7.c3 Nd7',
                self::$validData[2], '1.e4  c5   2.Nf3   Nc6 3.d4     cxd4 4   .  Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
                self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+    6.bxc3 b6   7.Bd3   Bb7   8.f3   c5',
                self::$validData[4], '1.Nf3   Nf6 2.c4   c5  3.g3  b6  4.Bg2  Bb7  5.O-O e6 6.Nc3 a6 7.d4  cxd4  8.Qxd4  d6',
            ],
        ];
    }

    public function wrongNumbersData()
    {
        return [
            [
                '2.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
                '1.e4 Nf6 2.e5 Nd5 4.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7',
                'e4 c5 2.Nf3 Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
                '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 23.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5',
                '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 10.Qxd4 d6',
            ],
        ];
    }

    public function invalidMovesData()
    {
        return [
            [
                '1.d4 Nf6 2.Nf3 FOO 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
                '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.BAR c6 6.Be2 Bf5 7.c3 Nd7',
                '1.e4 c5 2.Nf3 Nc6 3.FOO cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
                '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 BAR',
                '1.Nf3 Nf6 2.c4 c5 3.g3 BAR 4.Bg2 FOO 5.O-O e6 6.FOOBAR 7.d4 cxd4 8.Qxd4 d6',
            ],
        ];
    }

    public function validFIDEData()
    {
        return [
            [
                '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 0-0 5.a3 Be7 6.e4 d6 7.Bd3 c5',
                '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 0-0 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5',
                '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.0-0 e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6',
            ]
        ];
    }
}
