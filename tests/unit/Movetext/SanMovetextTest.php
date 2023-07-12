<?php

namespace Chess\Tests\Unit\Movetext;

use Chess\Movetext\SanMovetext;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Move;

class SanMovetextTest extends AbstractUnitTestCase
{
    static private $move;

    public static function setUpBeforeClass(): void
    {
        self::$move = new Move();
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

    public static $filteredData = [
        '{This is foo} 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5',
        '1.e4 Nf6 {This is foo} 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7',
        '1.e4 c5 2.Nf3 {This is foo} Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3',
        '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 {This is foo}',
        '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 {This is foo} d6',
    ];

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new SanMovetext(self::$move, 'foo'))->validate();
    }

    /**
     * @test
     */
    public function get_movetext()
    {
        $movetext = '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5';

        $expected = [ 'd4', 'Nf6', 'Nf3', 'e6', 'c4', 'Bb4+', 'Nbd2', 'O-O', 'a3', 'Be7', 'e4', 'd6', 'Bd3', 'c5' ];

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getMoves());
    }

    /**
     * @test
     */
    public function get_metadata_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

        $expected = (object) [
            'number' => (object) [
                'first' => 1,
                'last' => 11,
                'current' => 11,
            ],
            'turn' => (object) [
                'start' => 'w',
                'end' => 'w',
                'current' => 'b',
            ],
        ];

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getMetadata());
    }

    /**
     * @test
     */
    public function get_first_move_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

        $expected = '1.e4';

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getFirstMove());
    }

    /**
     * @test
     */
    public function get_last_move_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

        $expected = '11.Nd5';

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getLastMove());
    }

    /**
     * @test
     */
    public function get_last_move_e4_e5__Nf6()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6';

        $expected = '3.Bb5 Nf6';

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getLastMove());
    }

    /**
     * @test
     */
    public function get_first_move_Kb4_Kd3()
    {
        $movetext = '3...Kb4 4.Kd3';

        $expected = '3...Kb4';

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getFirstMove());
    }

    /**
     * @test
     */
    public function get_metadata_a5__Nxg4()
    {
        $movetext = '12...a5 13.g4 Nxg4';

        $expected = (object) [
            'number' => (object) [
                'first' => 12,
                'last' => 13,
                'current' => 14,
            ],
            'turn' => (object) [
                'start' => 'b',
                'end' => 'b',
                'current' => 'w',
            ],
        ];

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getMetadata());
    }

    /**
     * @test
     */
    public function get_metadata_Kb8()
    {
        $movetext = '6...Kb8';

        $expected = (object) [
            'number' => (object) [
                'first' => 6,
                'last' => 6,
                'current' => 7,
            ],
            'turn' => (object) [
                'start' => 'b',
                'end' => 'b',
                'current' => 'w',
            ],
        ];

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getMetadata());
    }

    /**
     * @test
     */
    public function get_metadata_Rh5()
    {
        $movetext = '3.Rh5';

        $expected = (object) [
            'number' => (object) [
                'first' => 3,
                'last' => 3,
                'current' => 3,
            ],
            'turn' => (object) [
                'start' => 'w',
                'end' => 'w',
                'current' => 'b',
            ],
        ];

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getMetadata());
    }

    /**
     * @test
     */
    public function filtered_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . Ra7 Kg8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $expected = "1.Ra7 Kg8 2.Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function validate_with_nag_2_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . Ra7 $2 Kg8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $expected = "1.Ra7 $2 Kg8 2.Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $sanMovetext = new SanMovetext(self::$move, $movetext);

        $sanMovetext->validate();

        $this->assertEquals($expected, $sanMovetext->filtered());
    }

    /**
     * @test
     */
    public function validate_with_nag_1000_Ra7_Kg8__Kf3()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $movetext = "1  . Ra7 $1000 Kg8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        (new SanMovetext(self::$move, $movetext))->validate();
    }

    /**
     * @test
     */
    public function filtered_with_nag_2_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . Ra7 $2 Kg8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $expected = "1.Ra7 $2 Kg8 2.Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function filtered_without_nag_2_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . Ra7 $2 Kg8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $expected = "1.Ra7 Kg8 2.Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} Kf8 3.Kf3";

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->filtered($nags = false));
    }

    /**
     * @test
     */
    public function filtered_without_nags_e4_e5__Nd5()
    {
        $movetext = '1.e4 $1 e5 $2 2.Nf3 Nc6 3.Bb5 $3 Nf6 4.Nc3 Be7 5.d3 $4 d6 6.Be3 Bd7 $5 7.Qd2 a6 8.Ba4 b5 $10 9.Bb3 O-O 10.O-O-O $21 b4 11.Nd5';

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->filtered($nags = false));
    }

    /**
     * @test
     */
    public function get_moves_e4_c6__Nf3_dxe4_commented()
    {
        $movetext = '1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack } 3...dxe4';

        $expected = [
            'e4',
            'c6',
            'Nc3',
            'd5',
            'Nf3',
            '...',
            'dxe4',
        ];

        $this->assertEquals($expected, (new SanMovetext(self::$move, $movetext))->getMoves());
    }

    /**
     * @dataProvider sequenceData
     * @test
     */
    public function sequence($movetext, $expected)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->sequence());
    }

    /**
     * @dataProvider validData
     * @test
     */
    public function valid($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider invalidMovesData
     * @test
     */
    public function invalid_moves($movetext)
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new SanMovetext(self::$move, $movetext))->validate();
    }

    /**
     * @dataProvider validateCurlyBracesData
     * @test
     */
    public function validate_curly_braces($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider filteredData
     * @test
     */
    public function filtered($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @dataProvider validateParenthesesData
     * @test
     */
    public function validate_parentheses($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider validateTooManySpacesData
     * @test
     */
    public function validate_too_many_spaces($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider validateFideData
     * @test
     */
    public function validate_fide($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider withResultData
     * @test
     */
    public function validate_with_result($expected, $movetext)
    {
        $this->assertSame($expected, (new SanMovetext(self::$move, $movetext))->validate());
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
            [ self::$validData[7], self::$validData[7] ],
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

    public function validateCurlyBracesData()
    {
        return [
            [ self::$validData[0], '{This is foo} 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 {This is foo} 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4 c5 2.Nf3 {This is foo} Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 {This is foo}' ],
            [ self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 {This is foo} d6' ],
        ];
    }

    public function validateParenthesesData()
    {
        return [
            [ self::$validData[0], '(This is foo) 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 (This is foo) 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4 c5 2.Nf3 (This is foo) Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 (This is foo)' ],
            [ self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 (This is foo) d6' ],
        ];
    }

    public function validateTooManySpacesData()
    {
        return [
            [ self::$validData[0], '1  .  d4    Nf6 2.Nf3 e6 3.c4    Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 2.   e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.   Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4  c5   2.Nf3   Nc6 3.d4     cxd4 4   .  Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+    6.bxc3 b6   7.Bd3   Bb7   8.f3   c5' ],
            [ self::$validData[4], '1.Nf3   Nf6 2.c4   c5  3.g3  b6  4.Bg2  Bb7  5.O-O e6 6.Nc3 a6 7.d4  cxd4  8.Qxd4  d6' ],
        ];
    }

    public function validateFideData()
    {
        return [
            [ self::$validData[0], '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 0-0 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$validData[1], '1.e4 Nf6 2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$validData[2], '1.e4 c5 2.Nf3 Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$validData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 0-0 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5' ],
            [ self::$validData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.0-0 e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 d6' ],
        ];
    }

    public function filteredData()
    {
        return [
            [ self::$filteredData[0], '   {This is foo} 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5' ],
            [ self::$filteredData[1], '1.e4 Nf6 {This is foo}     2.e5 Nd5 3.d4 d6 4.Nf3 dxe5 5.Nxe5 c6 6.Be2 Bf5 7.c3 Nd7' ],
            [ self::$filteredData[2], '1.e4    c5 2  .  Nf3 {This is foo} Nc6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 e5 6.Ndb5 d6 7.Bg5 a6 8.Na3' ],
            [ self::$filteredData[3], '1.d4 Nf6 2.c4 e6 3.Nc3 Bb4 4.e3 O-O 5.a3 Bxc3+ 6.bxc3 b6 7.Bd3 Bb7 8.f3 c5 {This is foo}' ],
            [ self::$filteredData[4], '1.Nf3 Nf6 2.c4 c5 3.g3 b6 4.Bg2 Bb7 5.O-O e6 6.Nc3 a6 7.d4 cxd4 8.Qxd4 {This is foo} d6' ],
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
