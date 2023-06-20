<?php

namespace Chess\Play;

use Chess\Exception\PlayException;
use Chess\Variant\Classical\Board;

/**
 * Long algebraic notation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class LAN extends AbstractPlay
{
    /**
     * Constructor.
     *
     * @param string $movetext
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(string $movetext, Board $board = null)
    {
        $board ? $this->board = $board : $this->board = new Board();
        $this->moves = array_values(array_filter(explode(' ', $movetext)));
        $this->fen = [(new Board())->toFen()];
    }

    /**
     * Plays a chess game.
     *
     * @return \Chess\Play\LAN
     */
    public function play(): LAN
    {
        foreach ($this->moves as $key => $val) {
            if ($key % 2 === 0) {
                if (!$this->board->playLan('w', $val)) {
                    throw new PlayException();
                }
            } else {
                if (!$this->board->playLan('b', $val)) {
                    throw new PlayException();
                }
            }
            $this->fen[] = $this->board->toFen();
        }

        return $this;
    }
}
