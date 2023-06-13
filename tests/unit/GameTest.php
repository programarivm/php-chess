<?php

namespace Chess\Tests\Unit;

use Chess\Game;
use Chess\Movetext;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;

class GameTest extends AbstractUnitTestCase
{
    /*
    |---------------------------------------------------------------------------
    | Play sample games.
    |---------------------------------------------------------------------------
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
                $board = (new StrToBoard('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -'))
                    ->create();
                $game = (new Game(Game::VARIANT_CLASSICAL, Game::MODE_FEN))
                    ->setBoard($board);
                foreach ($movetext->getMoves() as $key => $val) {
                    $this->assertTrue($game->play($game->getBoard()->getTurn(), $val));
                }
            }
        }
    }
}
