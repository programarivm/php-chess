<?php

namespace Chess\Player;

use Chess\Exception\PlayerException;
use Chess\Movetext\SAN;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Capablanca\PGN\Move as CapablancaPgnMove;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;

/**
 * PgnPlayer.
 *
 * Plays a chess game in PGN format.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class PgnPlayer extends AbstractPlayer
{
    /**
     * Constructor.
     *
     * @param string $movetext
     * @param ClassicalBoard $board
     */
    public function __construct(string $movetext, ClassicalBoard $board = null)
    {
        $this->board = $board ?? new ClassicalBoard();

        if (is_a($board, CapablancaBoard::class)) {
            $san = new SAN(new CapablancaPgnMove(), $movetext);
        } else {
            $san = new SAN(new ClassicalPgnMove(), $movetext);
        }

        $san->validate();

        $this->moves = $san->getMoves();
        $this->fen = [(new ClassicalBoard())->toFen()];
    }

    /**
     * Plays a chess game.
     *
     * @return \Chess\Player\PgnPlayer
     */
    public function play(): PgnPlayer
    {
        foreach ($this->moves as $key => $val) {
            if (!$this->board->play($this->board->getTurn(), $val)) {
                throw new PlayerException();
            }
            $this->fen[] = $this->board->toFen();
        }

        return $this;
    }
}
