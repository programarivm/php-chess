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

        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
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
        $this->assertEquals(false, $game->undoMove());
    }

    /**
     * @test
     */
    public function e4_e5_fen()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');

        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
    }

    /**
     * @test
     */
    public function e4_Bg6_fen()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');

        $this->assertEquals(false, $game->playFen('rnbqk1nr/pppppppp/6b1/8/4P3/8/PPPP1PPP/RNBQKBNR w'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_pgn_fen()
    {
        $game = new Game();
        $game->play('w', 'e4');
        $game->play('b', 'e5');

        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
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
    public function castling()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b');
        $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w');
        $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQK2R b');
        $game->playFen('rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQK2R w');

        $this->assertEquals(true, $game->playFen('rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQ1RK1 b'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_Bc4_Bc5_Ke2()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPPKPPP/RNBQ3R b'));
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_Bc4_Bc5_Ke2_Ke7_Nc3()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pppp1ppp/5n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('rnbqk2r/pppp1ppp/5n2/2b1p3/2B1P3/5N2/PPPPKPPP/RNBQ3R b'));
        $this->assertEquals(true, $game->playFen('rnbq3r/ppppkppp/5n2/2b1p3/2B1P3/5N2/PPPPKPPP/RNBQ3R w'));
        $this->assertEquals(true, $game->playFen('rnbq3r/ppppkppp/5n2/2b1p3/2B1P3/2N2N2/PPPPKPPP/R1BQ3R b'));
    }

    /**
     * @test
     */
    public function e4_c5_e5_f5_exf6()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1ppppp/8/2p1P3/8/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1pp1pp/8/2p1Pp2/8/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1pp1pp/5P2/2p5/8/8/PPPP1PPP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function Nf3_Nf6_d3_d6_Nfd2()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/8/5N2/PPPPPPPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pppppppp/5n2/8/8/5N2/PPPPPPPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pppppppp/5n2/8/8/3P1N2/PPP1PPPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/ppp1pppp/3p1n2/8/8/3P1N2/PPP1PPPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/ppp1pppp/3p1n2/8/8/3P4/PPPNPPPP/RNBQKB1R b'));
    }

    /**
     * @test
     */
    public function a4_h5_a5_h4_a6_h3_axb7_hxg2()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/P7/8/1PPPPPPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppppppp1/8/7p/P7/8/1PPPPPPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppppppp1/8/P6p/8/8/1PPPPPPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppppppp1/8/P7/7p/8/1PPPPPPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppppppp1/P7/8/7p/8/1PPPPPPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppppppp1/P7/8/8/7p/1PPPPPPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pPppppp1/8/8/8/7p/1PPPPPPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pPppppp1/8/8/8/8/1PPPPPpP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('Qnbqkbnr/p1ppppp1/8/8/8/8/1PPPPPpP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_dxe6()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp1pppp/8/3P4/8/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp2ppp/8/3Pp3/8/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp2ppp/4P3/8/8/8/PPPP1PPP/RNBQKBNR b'));
    }

    /**
     * @test
     */
    public function e4_d5_exd5_e5_then_get_piece()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp1pppp/8/3P4/8/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp2ppp/8/3Pp3/8/8/PPPP1PPP/RNBQKBNR w'));

        $this->assertEquals('P', $game->piece('d5')->identity);
    }

    /**
     * @test
     */
    public function e4_d5_Bb5()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp1pppp/8/3p4/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/ppp1pppp/8/1B1p4/4P3/8/PPPP1PPP/RNBQK1NR b'));

        $expected = '1.e4 d5 2.Bb5+';

        $this->assertEquals($expected, $game->movetext());
    }

    /**
     * @test
     */
    public function f4_e5_g4_Qh4()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/5P2/8/PPPPP1PP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/5P2/8/PPPPP1PP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/5PP1/8/PPPPP2P/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnb1kbnr/pppp1ppp/8/4p3/5PPq/8/PPPPP2P/RNBQKBNR w'));

        $expected = '1.f4 e5 2.g4 Qh4#';

        $this->assertEquals($expected, $game->movetext());
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_d4_cxd4_Nxd4_Nf6_then_get_piece()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w'));

        $this->assertNotEmpty($game->piece('b1')->moves);
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_d4_cxd4_Nxd4_Nf6_Nc3()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b'));
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

        $this->assertEquals($expected, $ascii);
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

        $this->assertEquals($expected, $ascii);
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

        $this->assertEquals($expected, $ascii);
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

        $this->assertEquals($expected, $ascii);
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
            'identity' => 'P',
            'position' => 'a7',
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
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/B3P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R b'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R w'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R b'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R w'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R b'));
        $this->assertEquals(true, $game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R w'));
        $this->assertEquals(true, $game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R b'));
        $this->assertEquals(true, $game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R w'));
        $this->assertEquals('O-O-O', $game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R1K4R b'));

        $ascii = $game->ascii();

        $expected = " r  .  .  .  k  .  .  r \n" .
                    " .  b  p  q  b  p  .  p \n" .
                    " p  .  n  p  .  n  p  . \n" .
                    " .  p  .  .  p  .  B  . \n" .
                    " P  .  .  .  P  .  .  . \n" .
                    " .  B  N  P  .  N  .  . \n" .
                    " .  P  P  .  Q  P  P  P \n" .
                    " .  .  K  R  .  .  .  R \n";

        $this->assertEquals($expected, $ascii);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nc6_Bb5_a6_Ba4_b5_Bb3_Bb7_a4_Nf6_Nc3_g6_Qe2_d6_d3_Be7_Bg5_Qd7_O_O_O_O_O()
    {
        $game = new Game();
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w'));
        $this->assertEquals(true, $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/B3P3/5N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r1bqkbnr/2pp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r2qkbnr/1bpp1ppp/p1n5/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R b'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1B3N2/1PPP1PPP/RNBQK2R w'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1ppp/p1n2n2/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R b'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPP1PPP/R1BQK2R w'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bpp1p1p/p1n2np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R b'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BN2N2/1PPPQPPP/R1B1K2R w'));
        $this->assertEquals(true, $game->playFen('r2qkb1r/1bp2p1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R b'));
        $this->assertEquals(true, $game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p3/P3P3/1BNP1N2/1PP1QPPP/R1B1K2R w'));
        $this->assertEquals(true, $game->playFen('r2qk2r/1bp1bp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R b'));
        $this->assertEquals(true, $game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R3K2R w'));
        $this->assertEquals('O-O-O', $game->playFen('r3k2r/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/R1K4R b'));
        $this->assertEquals('O-O', $game->playFen('r5kr/1bpqbp1p/p1np1np1/1p2p1B1/P3P3/1BNP1N2/1PP1QPPP/2KR3R w'));

        $ascii = $game->ascii();

        $expected = " r  .  .  .  .  r  k  . \n" .
                    " .  b  p  q  b  p  .  p \n" .
                    " p  .  n  p  .  n  p  . \n" .
                    " .  p  .  .  p  .  B  . \n" .
                    " P  .  .  .  P  .  .  . \n" .
                    " .  B  N  P  .  N  .  . \n" .
                    " .  P  P  .  Q  P  P  P \n" .
                    " .  .  K  R  .  .  .  R \n";

        $this->assertEquals($expected, $ascii);
    }
}
