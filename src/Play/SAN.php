<?php

namespace Chess\Play;

use Chess\Exception\PlayException;
use Chess\Movetext\SAN as SanMovetext;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Capablanca\PGN\Move as CapablancaPgnMove;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;

/**
 * Standard Algebraic Notation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class SAN extends AbstractPlay
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
            $san = new SanMovetext(new CapablancaPgnMove(), $movetext);
        } else {
            $san = new SanMovetext(new ClassicalPgnMove(), $movetext);
        }

        $san->validate();

        $this->moves = $san->getMoves();
        $this->fen = [(new ClassicalBoard())->toFen()];
    }

    /**
     * Plays a chess game.
     *
     * @return \Chess\Play\SAN
     */
    public function play(): SAN
    {
        foreach ($this->moves as $key => $val) {
            if (!$this->board->play($this->board->getTurn(), $val)) {
                throw new PlayException();
            }
            $this->fen[] = $this->board->toFen();
        }

        return $this;
    }
}
