<?php

namespace Chess\Tests\Unit;

use Chess\Game;
use Chess\Movetext;
use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\Board;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;

class GameTest extends AbstractUnitTestCase
{
    /*
    |--------------------------------------------------------------------------
    | ascii()
    |--------------------------------------------------------------------------
    |
    | Prints the ASCII representation of the game.
    |
    */

    /**
     * @test
     */
    public function ascii_kaufman_01()
    {
        $game = new Game(
            Game::VARIANT_CLASSICAL,
            Game::MODE_FEN
        );

        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');

        $ascii = $game->getBoard()->toAsciiString();

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
    public function ascii_kaufman_01_Qg4()
    {
        $game = new Game(
            Game::VARIANT_CLASSICAL,
            Game::MODE_FEN
        );

        $game->loadFen('1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+');
        $game->play('w', 'Qg4');

        $ascii = $game->getBoard()->toAsciiString();

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

    /*
    |--------------------------------------------------------------------------
    | loadFen()
    |--------------------------------------------------------------------------
    |
    | Loads a FEN string allowing to continue a chess game.
    |
    */

    /**
     * @test
     */
    public function loadFen_r3k2r__1R1Q1RK1()
    {
        $game = new Game(
            Game::VARIANT_CLASSICAL,
            Game::MODE_FEN
        );

        $game->loadFen('r3k2r/pp1np1bp/2p5/q4p2/2BPpB2/1PP1P3/6PP/1R1Q1RK1 b kq -');

        $this->assertTrue($game->play('b', 'O-O-O'));
    }

    /*
    |--------------------------------------------------------------------------
    | Play sample games.
    |--------------------------------------------------------------------------
    |
    | Plays the PGN games that are found in the tests/data/pgn folder.
    |
    */

    /**
     * @test
     */
    public function play_games()
    {
        $move = new ClassicalPgnMove();
        foreach (new \DirectoryIterator(self::DATA_FOLDER."/pgn/") as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $filename = $fileInfo->getFilename();
            $text = file_get_contents(self::DATA_FOLDER."/pgn/$filename");
            $movetext = new Movetext($move, $text);
            if ($movetext->validate()) {
                $game = new Game(Game::VARIANT_CLASSICAL, Game::MODE_FEN);
                $game->loadFen('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');
                foreach ($movetext->getMovetext()->moves as $key => $val) {
                    $this->assertTrue($game->play($game->getBoard()->getTurn(), $val));
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | playLan()
    |--------------------------------------------------------------------------
    |
    | Makes a move in UCI format.
    |
    */

    /**
     * @test
     */
    public function classical_play_uci_B00()
    {
        $game = new Game(
            Game::VARIANT_CLASSICAL,
            Game::MODE_FEN
        );

        $game->loadFen('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');

        $this->assertTrue($game->playLan('w', 'e2e4'));
        $this->assertTrue($game->playLan('b', 'b8c6'));
        $this->assertTrue($game->playLan('w', 'g1f3'));
        $this->assertTrue($game->playLan('b', 'd7d6'));
        $this->assertTrue($game->playLan('w', 'f1e2'));
        $this->assertTrue($game->playLan('b', 'c8e6'));
        $this->assertTrue($game->playLan('w', 'e1g1'));
        $this->assertTrue($game->playLan('b', 'd8d7'));
        $this->assertTrue($game->playLan('w', 'h2h3'));
        $this->assertTrue($game->playLan('b', 'e8c8'));
    }
}
