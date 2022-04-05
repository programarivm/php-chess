<?php

namespace Chess\Tests\Unit\Game;

use Chess\Ascii;
use Chess\Game;
use Chess\Tests\AbstractUnitTestCase;

class PlayTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_fen()
    {
        $game = new Game();

        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function e4_fen_undo_move()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');

        $this->assertIsObject($game->undoMove());
    }

    /**
     * @test
     */
    public function e4_fen_undo_two_moves()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');

        $this->assertIsObject($game->undoMove());
        $this->assertNull($game->undoMove());
    }

    /**
     * @test
     */
    public function e4_e5_fen()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');

        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
    }

    /**
     * @test
     */
    public function e4_Bg6_fen()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');

        $this->assertFalse($game->playFen('rnbqk1nr/pppppppp/6b1/8/4P3/8/PPPP1PPP/RNBQKBNR w'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_pgn_fen()
    {
        $game = new Game();
        $game->play('w', 'e4');
        $game->play('b', 'e5');

        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_fen_pgn()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w');

        $this->assertTrue(
            $game->play('w', 'Nf3')
        );
    }

    /**
     * @test
     */
    public function castle()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b');
        $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w');
        $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQK2R b');
        $game->playFen('rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQK2R w');

        $this->assertSame('O-O', $game->playFen('rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQ2KR b'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_Bc4_Bc5_Ke2()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPPKPPP/RNBQ3R b'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_Bc4_Bc5_Ke2_Ke7_Nc3()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPPKPPP/RNBQ3R b'));
        $this->assertTrue($game->playFen('rnbq3r/ppppkppp/5n2/2b1p3/2B1P3/5N2/PPPPKPPP/RNBQ3R w'));
        $this->assertTrue($game->playFen('rnbq3r/ppppkppp/5n2/2b1p3/2B1P3/2N2N2/PPPPKPPP/R1BQ3R b'));
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5_exf6()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1ppppp/8/2p1P3/8/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1pp1pp/5P2/2p5/8/8/PPPP1PPP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function Nf3_Nf6_d3_d6_Nfd2()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/8/5N2/PPPPPPPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkb1r/pppppppp/5n2/8/8/5N2/PPPPPPPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkb1r/pppppppp/5n2/8/8/3P1N2/PPP1PPPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkb1r/ppp1pppp/3p1n2/8/8/3P1N2/PPP1PPPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkb1r/ppp1pppp/3p1n2/8/8/3P4/PPPNPPPP/RNBQKB1R b'));
    }

    /**
     * @test
     */
    public function a4_h5_a5_h4_a6_h3_axb7_hxg2()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/P7/8/1PPPPPPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppppppp1/8/7p/P7/8/1PPPPPPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppppppp1/8/P6p/8/8/1PPPPPPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppppppp1/8/P7/7p/8/1PPPPPPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppppppp1/P7/8/7p/8/1PPPPPPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppppppp1/P7/8/8/7p/1PPPPPPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pPppppp1/8/8/8/7p/1PPPPPPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pPppppp1/8/8/8/8/1PPPPPpP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('Qnbqkbnr/p1ppppp1/8/8/8/8/1PPPPPpP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_dxe6()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp1pppp/8/3P4/8/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp2ppp/8/3Pp3/8/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp2ppp/4P3/8/8/8/PPPP1PPP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_then_get_piece()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp1pppp/8/3P4/8/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp2ppp/8/3Pp3/8/8/PPPP1PPP/RNBQKBNR w'));

        $this->assertSame('P', $game->piece('d5')->id);
    }

    /**
     * @test
     */
    public function e4_d5_Bb5()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/ppp1pppp/8/1B1p4/4P3/8/PPPP1PPP/RNBQK1NR b'));

        $expected = '1.e4 d5 2.Bb5+';

        $this->assertSame($expected, $game->movetext());
    }

    /**
     * @test
     */
    public function f4_e5_g4_Qh4()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/5P2/8/PPPPP1PP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/5P2/8/PPPPP1PP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/5PP1/8/PPPPP2P/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnb1kbnr/pppp1ppp/8/4p3/5PPq/8/PPPPP2P/RNBQKBNR w'));

        $expected = '1.f4 e5 2.g4 Qh4#';

        $this->assertSame($expected, $game->movetext());
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_d4_cxd4_Nxd4_Nf6_then_get_piece()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w'));

        $this->assertNotEmpty($game->piece('b1')->moves);
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_d4_cxd4_Nxd4_Nf6_Nc3()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b'));
    }

    /**
     * @test
     */
    public function kaufman_01()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');

        $ascii = $game->ascii();

        $expected = " .  r  b  q  .  r  k  . \n" .
                    " p  .  b  .  n  p  p  p \n" .
                    " .  p  .  .  p  .  .  . \n" .
                    " .  .  .  .  .  .  .  . \n" .
                    " .  B  .  p  N  .  .  . \n" .
                    " P  .  .  B  .  .  .  . \n" .
                    " .  P  .  .  .  P  P  P \n" .
                    " .  .  R  Q  .  R  .  K \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function kaufman_01_play_Qg4()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $game->play('w', 'Qg4');

        $ascii = $game->ascii();

        $expected = " .  r  b  q  .  r  k  . \n" .
                    " p  .  b  .  n  p  p  p \n" .
                    " .  p  .  .  p  .  .  . \n" .
                    " .  .  .  .  .  .  .  . \n" .
                    " .  B  .  p  N  .  Q  . \n" .
                    " P  .  .  B  .  .  .  . \n" .
                    " .  P  .  .  .  P  P  P \n" .
                    " .  .  R  .  .  R  .  K \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function kaufman_01_playFen_Qg4()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $game->playFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN1Q1/P2B4/1P3PPP/2R2R1K b');

        $ascii = $game->ascii();

        $expected = " .  r  b  q  .  r  k  . \n" .
                    " p  .  b  .  n  p  p  p \n" .
                    " .  p  .  .  p  .  .  . \n" .
                    " .  .  .  .  .  .  .  . \n" .
                    " .  B  .  p  N  .  Q  . \n" .
                    " P  .  .  B  .  .  .  . \n" .
                    " .  P  .  .  .  P  P  P \n" .
                    " .  .  R  .  .  R  .  K \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function kaufman_01_play_Qg4_a5()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $game->play('w', 'Qg4');
        $game->play('b', 'a5');

        $ascii = $game->ascii();

        $expected = " .  r  b  q  .  r  k  . \n" .
                    " .  .  b  .  n  p  p  p \n" .
                    " .  p  .  .  p  .  .  . \n" .
                    " p  .  .  .  .  .  .  . \n" .
                    " .  B  .  p  N  .  Q  . \n" .
                    " P  .  .  B  .  .  .  . \n" .
                    " .  P  .  .  .  P  P  P \n" .
                    " .  .  R  .  .  R  .  K \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function kaufman_01_play_Qg4_get_piece_a7()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $game->play('w', 'Qg4');

        $piece = $game->piece('a7');

        $expected = (object) [
            'color' => 'b',
            'id' => 'P',
            'sq' => 'a7',
            'moves' => [ 'a6', 'a5' ],
        ];

        $this->assertEquals($expected, $piece);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Ba4_b5_Bb3_Bb7_a4_Nf6_Nc3_g6_Qe2_d6_d3_Be7_Bg5_Qd7_O_O_O()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/B3P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R b'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R w'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R b'));
        $this->assertTrue($game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R w'));
        $this->assertTrue($game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R b'));
        $this->assertTrue($game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R w'));
        $this->assertTrue($game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R b'));
        $this->assertTrue($game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R w'));
        $this->assertSame('O-O-O', $game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R1K4R b'));

        $ascii = $game->ascii();

        $expected = " r  .  .  .  k  .  .  r \n" .
                    " .  b  p  q  b  p  .  p \n" .
                    " p  .  n  p  .  n  p  . \n" .
                    " .  p  .  .  p  .  B  . \n" .
                    " P  .  .  .  P  .  .  . \n" .
                    " .  B  N  P  .  N  .  . \n" .
                    " .  P  P  .  Q  P  P  P \n" .
                    " .  .  K  R  .  .  .  R \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Ba4_b5_Bb3_Bb7_a4_Nf6_Nc3_g6_Qe2_d6_d3_Be7_Bg5_Qd7_O_O_O_O_O()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/B3P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R b'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R w'));
        $this->assertTrue($game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R b'));
        $this->assertTrue($game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R w'));
        $this->assertTrue($game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R b'));
        $this->assertTrue($game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R w'));
        $this->assertTrue($game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R b'));
        $this->assertTrue($game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R w'));
        $this->assertSame('O-O-O', $game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R1K4R b'));
        $this->assertSame('O-O', $game->playFen('r5kr/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/2KR3R w'));

        $ascii = $game->ascii();

        $expected = " r  .  .  .  .  r  k  . \n" .
                    " .  b  p  q  b  p  .  p \n" .
                    " p  .  n  p  .  n  p  . \n" .
                    " .  p  .  .  p  .  B  . \n" .
                    " P  .  .  .  P  .  .  . \n" .
                    " .  B  N  P  .  N  .  . \n" .
                    " .  P  P  .  Q  P  P  P \n" .
                    " .  .  K  R  .  .  .  R \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8_Bg7_d3_Bxh8_Qe2_Nge7_c3()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1pp1/2n4p/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1pp1/2n4p/4p3/2B1P2P/5N2/PPPP1PP1/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1p2/2n4p/4p1p1/2B1P2P/5N2/PPPP1PP1/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1p2/2n4p/4p1P1/2B1P3/5N2/PPPP1PP1/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnR/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 b'));
        $this->assertTrue($game->playFen('r1bqk1nR/pppp1pb1/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 w'));
        $this->assertTrue($game->playFen('r1bqk1nR/pppp1pb1/2n5/4p1p1/2B1P3/3P1N2/PPP2PP1/RNBQK3 b'));
        $this->assertTrue($game->playFen('r1bqk1nb/pppp1p2/2n5/4p1p1/2B1P3/3P1N2/PPP2PP1/RNBQK3 w'));
        $this->assertTrue($game->playFen('r1bqk1nb/pppp1p2/2n5/4p1p1/2B1P3/3P1N2/PPP1QPP1/RNB1K3 b'));
        $this->assertTrue($game->playFen('r1bqk2b/ppppnp2/2n5/4p1p1/2B1P3/3P1N2/PPP1QPP1/RNB1K3 w'));
        $this->assertTrue($game->playFen('r1bqk2b/ppppnp2/2n5/4p1p1/2B1P3/2PP1N2/PP2QPP1/RNB1K3 b'));

        $ascii = $game->ascii();

        $expected = " r  .  b  q  k  .  .  b \n" .
                    " p  p  p  p  n  p  .  . \n" .
                    " .  .  n  .  .  .  .  . \n" .
                    " .  .  .  .  p  .  p  . \n" .
                    " .  .  B  .  P  .  .  . \n" .
                    " .  .  P  P  .  N  .  . \n" .
                    " P  P  .  .  Q  P  P  . \n" .
                    " R  N  B  .  K  .  .  . \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bc4_h6_h4_g5_hxg5_hxg5_Rxh8_Bg7_d3_Bxh8_Qe2_Nge7_c3_g4()
    {
        $game = new Game();
        $this->assertTrue($game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertTrue($game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1pp1/2n4p/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1pp1/2n4p/4p3/2B1P2P/5N2/PPPP1PP1/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1p2/2n4p/4p1p1/2B1P2P/5N2/PPPP1PP1/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1p2/2n4p/4p1P1/2B1P3/5N2/PPPP1PP1/RNBQK2R b'));
        $this->assertTrue($game->playFen('r1bqkbnr/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK2R w'));
        $this->assertTrue($game->playFen('r1bqkbnR/pppp1p2/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 b'));
        $this->assertTrue($game->playFen('r1bqk1nR/pppp1pb1/2n5/4p1p1/2B1P3/5N2/PPPP1PP1/RNBQK3 w'));
        $this->assertTrue($game->playFen('r1bqk1nR/pppp1pb1/2n5/4p1p1/2B1P3/3P1N2/PPP2PP1/RNBQK3 b'));
        $this->assertTrue($game->playFen('r1bqk1nb/pppp1p2/2n5/4p1p1/2B1P3/3P1N2/PPP2PP1/RNBQK3 w'));
        $this->assertTrue($game->playFen('r1bqk1nb/pppp1p2/2n5/4p1p1/2B1P3/3P1N2/PPP1QPP1/RNB1K3 b'));
        $this->assertTrue($game->playFen('r1bqk2b/ppppnp2/2n5/4p1p1/2B1P3/3P1N2/PPP1QPP1/RNB1K3 w'));
        $this->assertTrue($game->playFen('r1bqk2b/ppppnp2/2n5/4p1p1/2B1P3/2PP1N2/PP2QPP1/RNB1K3 b'));
        $this->assertTrue($game->playFen('r1bqk2b/ppppnp2/2n5/4p3/2B1P1p1/2PP1N2/PP2QPP1/RNB1K3 w'));

        $ascii = $game->ascii();

        $expected = " r  .  b  q  k  .  .  b \n" .
                    " p  p  p  p  n  p  .  . \n" .
                    " .  .  n  .  .  .  .  . \n" .
                    " .  .  .  .  p  .  .  . \n" .
                    " .  .  B  .  P  .  p  . \n" .
                    " .  .  P  P  .  N  .  . \n" .
                    " P  P  .  .  Q  P  P  . \n" .
                    " R  N  B  .  K  .  .  . \n";

        $this->assertSame($expected, $ascii);
    }

    /**
     * @test
     */
    public function endgame_checkmate_king_and_rook_vs_king_01()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('7k/8/8/8/8/8/2K5/r7 w - - 0 1');

        $this->assertTrue($game->playFen('7k/8/8/8/8/8/1K6/r7 b'));
        $this->assertTrue($game->playFen('8/6k1/8/8/8/8/1K6/r7 w'));
    }

    /**
     * @test
     */
    public function endgame_checkmate_king_and_rook_vs_king_02()
    {
        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen('7k/8/8/8/8/8/2K5/r7 w - - 0 1');

        $this->assertTrue($game->playFen('7k/8/8/8/8/2K5/8/r7 b'));
        $this->assertTrue($game->playFen('6k1/8/8/8/8/2K5/8/r7 w'));
    }
}
