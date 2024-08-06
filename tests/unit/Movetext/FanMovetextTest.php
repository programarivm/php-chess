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

        (new FanMovetext(self::$move, 'foo'))->validate();
    }

    /**
     * @test
     */
    public function get_movetext()
    {
        $movetext = '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5';

        $expected = [ 'd4', '♘f6', '♘f3', 'e6', 'c4', '♗b4+', '♘bd2', 'O-O', 'a3', '♗e7', 'e4', 'd6', '♗d3', 'c5' ];

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->moves);
    }

    /**
     * @test
     */
    public function get_metadata_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

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
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

        $expected = '1.e4';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['firstMove']);
    }

    /**
     * @test
     */
    public function get_last_move_e4_e5__Nd5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5';

        $expected = '11.♘d5';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['lastMove']);
    }

    /**
     * @test
     */
    public function get_last_move_e4_e5__Nf6()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6';

        $expected = '3.♗b5 ♘f6';

        $this->assertEquals($expected, (new FanMovetext(self::$move, $movetext))->metadata['lastMove']);
    }
}
