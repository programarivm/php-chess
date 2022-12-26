<?php

namespace Chess\Player;

use Chess\Exception\PlayerException;
use Chess\Variant\Classical\Board;

/**
 * LanPlayer.
 *
 * Plays a chess game in long algebraic notation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class LanPlayer extends AbstractPlayer
{
    /**
     * Constructor.
     *
     * @param string $text
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(string $text, Board $board = null)
    {
        $board ? $this->board = $board : $this->board = new Board();
        $this->moves = array_values(array_filter(explode(' ', $text)));
        $this->history = [array_values((new Board())->toAsciiArray())];
    }

    /**
     * Plays a chess game.
     *
     * @return \Chess\Player\LanPlayer
     */
    public function play(): LanPlayer
    {
        foreach ($this->moves as $key => $val) {
            if ($key % 2 === 0) {
                if (!$this->board->playLan('w', $val)) {
                    throw new PlayerException();
                }
            } else {
                if (!$this->board->playLan('b', $val)) {
                    throw new PlayerException();
                }
            }
            $this->history[] = array_values($this->board->toAsciiArray());
        }

        return $this;
    }
}
