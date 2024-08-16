<?php

namespace Chess\Tests\Unit\Movetext;

use Chess\Movetext\FanMovetext;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Move;

class FanMovetextTest extends AbstractUnitTestCase
{
    static private Move $move;

    public static function setUpBeforeClass(): void
    {
        self::$move = new Move();
    }

    public static $validData = [
        '1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5',
        '1.e4 ♘f6 2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.♗e2 ♗f5 7.c3 ♘d7',
        '1.e4 c5 2.♘f3 ♘c6 3.d4 cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3',
        '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 c5',
        '1.♘f3 ♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 d6',
        '1...♗g7 2.e4',
        '1...♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 d6',
        '2...c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 d6',
    ];

    public static $filteredData = [
        '{This is foo} 1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5',
        '1.e4 ♘f6 {This is foo} 2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.♗e2 ♗f5 7.c3 ♘d7',
        '1.e4 c5 2.♘f3 {This is foo} ♘c6 3.d4 cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3',
        '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 c5 {This is foo}',
        '1.♘f3 ♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 {This is foo} d6',
    ];

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new FanMovetext(self::$move, 'foo'))->validate();
    }

    /**
     * @test
     */
    public function get_movetext()
    {
        $movetext = '1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5';

        $expected = [ 'd4', '♘f6', '♘f3', 'e6', 'c4', '♗b4+', '♘bd2', 'O-O', 'a3', '♗e7', 'e4', 'd6', '♗d3', 'c5' ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->moves);
    }

    /**
     * @test
     */
    public function get_san_movetext()
    {
        $movetext = '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5';

        $expected = [ 'd4', '♘f6', '♘f3', 'e6', 'c4', '♗b4+', '♘bd2', 'O-O', 'a3', '♗e7', 'e4', 'd6', '♗d3', 'c5' ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->moves);
    }

    /**
     * @test
     */
    public function get_san_moves()
    {
        $movetext = '1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5';

        $expected = [ 'd4', 'Nf6', 'Nf3', 'e6', 'c4', 'Bb4+', 'Nbd2', 'O-O', 'a3', 'Be7', 'e4', 'd6', 'Bd3', 'c5' ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->sanMoves);
    }

    /**
     * @test
     */
    public function get_metadata_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.♘f3 ♘c6 3.♗b5 ♘f6 4.♘c3 ♗e7 5.d3 d6 6.♗e3 ♗d7 7.♕d2 a6 8.♗a4 b5 9.♗b3 O-O 10.O-O-O b4 11.♘d5';

        $expected = [
            'turn' => 'b',
            'firstMove' => '1.e4',
            'lastMove' => '11.♘d5',
        ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata);
    }

    /**
     * @test
     */
    public function get_first_move_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.♘f3 ♘c6 3.♗b5 ♘f6 4.♘c3 ♗e7 5.d3 d6 6.♗e3 ♗d7 7.♕d2 a6 8.♗a4 b5 9.♗b3 O-O 10.O-O-O b4 11.♘d5';

        $expected = '1.e4';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['firstMove']);
    }

    /**
     * @test
     */
    public function get_last_move_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.♘f3 ♘c6 3.♗b5 ♘f6 4.♘c3 ♗e7 5.d3 d6 6.♗e3 ♗d7 7.♕d2 a6 8.♗a4 b5 9.♗b3 O-O 10.O-O-O b4 11.♘d5';

        $expected = '11.♘d5';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['lastMove']);
    }

    /**
     * @test
     */
    public function get_last_move_e4_e5__Nf6()
    {
        $movetext = '1.e4 e5 2.♘f3 ♘c6 3.♗b5 ♘f6';

        $expected = '3.♗b5 ♘f6';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['lastMove']);
    }

    /**
     * @test
     */
    public function get_first_move_Kb4_Kd3()
    {
        $movetext = '3...♔b4 4.♔d3';

        $expected = '3...♔b4';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['firstMove']);
    }

    /**
     * @test
     */
    public function get_metadata_a5__Nxg4()
    {
        $movetext = '12...a5 13.g4 ♘xg4';

        $expected = [
            'turn' => 'w',
            'firstMove' => '12...a5',
            'lastMove' => '13.g4 ♘xg4',
        ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata);
    }

    /**
     * @test
     */
    public function get_metadata_Kb8()
    {
        $movetext = '6...♔b8';

        $expected = [
            'turn' => 'w',
            'firstMove' => '6...♔b8',
            'lastMove' => '6...♔b8',
        ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata);
    }

    /**
     * @test
     */
    public function get_metadata_Rh5()
    {
        $movetext = '3.♖h5';

        $expected = [
            'turn' => 'b',
            'firstMove' => '3.♖h5',
            'lastMove' => '3.♖h5',
        ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata);
    }

    /**
     * @test
     */
    public function filtered_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . ♖a7 ♔g8 2 .♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $expected = "1.♖a7 ♔g8 2.♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function validate_with_nag_2_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . ♖a7 $2 ♔g8 2 .Kg2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $expected = "1.♖a7 $2 ♔g8 2.♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $fanMovetext = new FanMovetext(self::$move, $movetext);

        $fanMovetext->validate();

        $this->assertEquals($expected, $fanMovetext->filtered());
    }

    /**
     * @test
     */
    public function validate_with_nag_1000_Ra7_Kg8__Kf3()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $movetext = "1  . ♖a7 $1000 ♔g8 2 .♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        (new FanMovetext(self::$move, $movetext))->validate();
    }

    /**
     * @test
     */
    public function filtered_with_nag_2_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . ♖a7 $2 ♔g8 2 .♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $expected = "1.♖a7 $2 ♔g8 2.♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @test
     */
    public function filtered_without_nag_2_Ra7_Kg8__Kf3()
    {
        $movetext = "1  . ♖a7 $2 ♔g8 2 .♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $expected = "1.♖a7 ♔g8 2.♔g2 {activates the White king. The combined action of King and Rook is needed to arrive at a position in which mate can be forced.} ♔f8 3.♔f3";

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->filtered($comments = true, $nags = false));
    }

    /**
     * @test
     */
    public function filtered_without_nags_e4_e5__Nd5()
    {
        $movetext = '1.e4 $1 e5 $2 2.♘f3 ♘c6 3.♗b5 $3 ♘f6 4.♘c3 ♗e7 5.d3 $4 d6 6.♗e3 ♗d7 $5 7.♕d2 a6 8.♗a4 b5 $10 9.♗b3 O-O 10.O-O-O $21 b4 11.♘d5';

        $expected = '1.e4 e5 2.♘f3 ♘c6 3.♗b5 ♘f6 4.♘c3 ♗e7 5.d3 d6 6.♗e3 ♗d7 7.♕d2 a6 8.♗a4 b5 9.♗b3 O-O 10.O-O-O b4 11.♘d5';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->filtered($comments = true, $nags = false));
    }

    /**
     * @test
     */
    public function get_moves_e4_c6__Nf3_dxe4_commented()
    {
        $movetext = '1. e4 c6 2. ♘c3 d5 3. ♘f3 { B10 Caro-Kann Defense: Two Knights Attack } 3...dxe4';

        $expected = [
            'e4',
            'c6',
            '♘c3',
            'd5',
            '♘f3',
            '...',
            'dxe4',
        ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->moves);
    }

    /**
     * @dataProvider validData
     * @test
     */
    public function valid($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider invalidMovesData
     * @test
     */
    public function invalid_moves($movetext)
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new FanMovetext(self::$move, $movetext))->validate();
    }

    /**
     * @dataProvider validateCurlyBracesData
     * @test
     */
    public function validate_curly_braces($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider filteredData
     * @test
     */
    public function filtered($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->filtered());
    }

    /**
     * @dataProvider validateParenthesesData
     * @test
     */
    public function validate_parentheses($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider validateTooManySpacesData
     * @test
     */
    public function validate_too_many_spaces($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider validateFideData
     * @test
     */
    public function validate_fide($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->validate());
    }

    /**
     * @dataProvider withResultData
     * @test
     */
    public function validate_with_result($expected, $movetext)
    {
        $this->assertSame($expected, (new FanMovetext(self::$move, $movetext))->validate());
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
            [ '1.d4 ♘f6 2.♘f3 FOO 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5' ],
            [ '1.e4 ♘f6 2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♗AR c6 6.♗e2 ♗f5 7.c3 ♘d7' ],
            [ '1.e4 c5 2.♘f3 ♘c6 3.FOO cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3' ],
            [ '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 BAR' ],
            [ '1.♘f3 ♘f6 2.c4 c5 3.g3 BAR 4.♗g2 FOO 5.O-O e6 6.FOOBAR 7.d4 cxd4 8.♕xd4 d6' ],
        ];
    }

    public function validateCurlyBracesData()
    {
        return [
            [ self::$validData[0], '{This is foo} 1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5' ],
            [ self::$validData[1], '1.e4 ♘f6 {This is foo} 2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.♗e2 ♗f5 7.c3 ♘d7' ],
            [ self::$validData[2], '1.e4 c5 2.♘f3 {This is foo} ♘c6 3.d4 cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3' ],
            [ self::$validData[3], '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 c5 {This is foo}' ],
            [ self::$validData[4], '1.♘f3 ♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 {This is foo} d6' ],
        ];
    }

    public function validateParenthesesData()
    {
        return [
            [ self::$validData[0], '(This is foo) 1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5' ],
            [ self::$validData[1], '1.e4 ♘f6 (This is foo) 2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.♗e2 ♗f5 7.c3 ♘d7' ],
            [ self::$validData[2], '1.e4 c5 2.♘f3 (This is foo) ♘c6 3.d4 cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3' ],
            [ self::$validData[3], '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 c5 (This is foo)' ],
            [ self::$validData[4], '1.♘f3 ♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 (This is foo) d6' ],
        ];
    }

    public function validateTooManySpacesData()
    {
        return [
            [ self::$validData[0], '1  .  d4    ♘f6 2.♘f3 e6 3.c4    ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5' ],
            [ self::$validData[1], '1.e4 ♘f6 2.   e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.   ♗e2 ♗f5 7.c3 ♘d7' ],
            [ self::$validData[2], '1.e4  c5   2.♘f3   ♘c6 3.d4     cxd4 4   .  ♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3' ],
            [ self::$validData[3], '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+    6.bxc3 b6   7.♗d3   ♗b7   8.f3   c5' ],
            [ self::$validData[4], '1.♘f3   ♘f6 2.c4   c5  3.g3  b6  4.♗g2  ♗b7  5.O-O e6 6.♘c3 a6 7.d4  cxd4  8.♕xd4  d6' ],
        ];
    }

    public function validateFideData()
    {
        return [
            [ self::$validData[0], '1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 0-0 5.a3 ♗e7 6.e4 d6 7.♗d3 c5' ],
            [ self::$validData[1], '1.e4 ♘f6 2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.♗e2 ♗f5 7.c3 ♘d7' ],
            [ self::$validData[2], '1.e4 c5 2.♘f3 ♘c6 3.d4 cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3' ],
            [ self::$validData[3], '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 0-0 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 c5' ],
            [ self::$validData[4], '1.♘f3 ♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.0-0 e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 d6' ],
        ];
    }

    public function filteredData()
    {
        return [
            [ self::$filteredData[0], '   {This is foo} 1.d4 ♘f6 2.♘f3 e6 3.c4 ♗b4+ 4.♘bd2 O-O 5.a3 ♗e7 6.e4 d6 7.♗d3 c5' ],
            [ self::$filteredData[1], '1.e4 ♘f6 {This is foo}     2.e5 ♘d5 3.d4 d6 4.♘f3 dxe5 5.♘xe5 c6 6.♗e2 ♗f5 7.c3 ♘d7' ],
            [ self::$filteredData[2], '1.e4    c5 2  .  ♘f3 {This is foo} ♘c6 3.d4 cxd4 4.♘xd4 ♘f6 5.♘c3 e5 6.♘db5 d6 7.♗g5 a6 8.♘a3' ],
            [ self::$filteredData[3], '1.d4 ♘f6 2.c4 e6 3.♘c3 ♗b4 4.e3 O-O 5.a3 ♗xc3+ 6.bxc3 b6 7.♗d3 ♗b7 8.f3 c5 {This is foo}' ],
            [ self::$filteredData[4], '1.♘f3 ♘f6 2.c4 c5 3.g3 b6 4.♗g2 ♗b7 5.O-O e6 6.♘c3 a6 7.d4 cxd4 8.♕xd4 {This is foo} d6' ],
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
}
