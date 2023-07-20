<?php

namespace Chess\Tests\Unit\Play;

use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;

class SanPlayTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5()
    {
        $movetext = '1.e4 e5';
        $board = (new SanPlay($movetext))->validate()->getBoard();

        $this->assertSame($movetext, $board->getMovetext());
    }

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $movetext = 'foo';
        $board = (new SanPlay($movetext))->validate()->getBoard();
    }

    /**
     * @test
     */
    public function e4_e4()
    {
        $this->expectException(\Chess\Exception\PlayException::class);

        $movetext = '1.e4 e4';
        $board = (new SanPlay($movetext))->validate()->getBoard();
    }

    /**
     * @test
     */
    public function ellipsis_Nc6_Bc4()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -';
        $board = (new ClassicalFenStrToBoard($fen))->create();
        $movetext = '2...Nc6 3.Bc4';
        $board = (new SanPlay($movetext, $board))->validate()->getBoard();
        $expected = '1...Nc6 2.Bc4';

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function filtered_e4_e5__h5()
    {
        $movetext = '1. e4 e5 {foo} 2. Nf3 {bar} Nc6 3. Bb5 Nf6 4. Nc3 Be7 5. d3 d6 6. Be3 Bd7 7. Qd2 a6 8. Ba4 b5 9. Bb3 O-O 10. O-O-O b4 11. Nd5 {foobar}';

        $expected = '1.e4 e5 {foo} 2.Nf3 {bar} Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 {foobar}';

        $this->assertSame($expected, (new SanPlay($movetext))->getSanMovetext()->filtered());
    }

    /**
     * @test
     */
    public function get_movetext_e4_c6__Nf3_dxe4_commented()
    {
        $movetext = '1. e4 c6 2. Nc3 d5 3. Nf3 { B10 Caro-Kann Defense: Two Knights Attack } 3...dxe4';

        $expected = '1.e4 c6 2.Nc3 d5 3.Nf3 dxe4';

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function validate_with_nags_e4_c6__Nf3_dxe4()
    {
        $movetext = '1. e4 $2 c6 2. Nc3 d5 3. Nf3 $4 3...dxe4';

        $expected = '1.e4 c6 2.Nc3 d5 3.Nf3 dxe4';

        $sanPlay = (new SanPlay($movetext))->validate();

        $this->assertSame($expected, $sanPlay->getBoard()->getMovetext());
    }

    /**
     * @test
     */
    public function get_movetext_e4_e5__h5()
    {
        $movetext = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 11...Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5';

        $expected = '1.e4 e5 2.Nf3 Nc6 3.Bb5 Nf6 4.Nc3 Be7 5.d3 d6 6.Be3 Bd7 7.Qd2 a6 8.Ba4 b5 9.Bb3 O-O 10.O-O-O b4 11.Nd5 Nxd5 12.Bxd5 Rb8 13.h4 h6 14.Rdg1 a5 15.g4 g5 16.h5';

        $board = (new SanPlay($movetext))->validate()->getBoard();

        $this->assertSame($expected, $board->getMovetext());
    }
}
