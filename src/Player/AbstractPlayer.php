<?php

namespace Chess\Player;

use Chess\Variant\Classical\Board;

/**
 * AbstractPlayer.
 *
 * Plays a chess game.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractPlayer
{
    /**
     * Chess board.
     *
     * @var \Chess\Variant\Classical\Board
     */
    protected Board $board;

    /**
     * Moves.
     *
     * @var array
     */
    protected array $moves;

    /**
     * History.
     *
     * @var array
     */
    protected array $history;

    /**
     * Returns the resulting board object of playing a game.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * Returns the moves as an array.
     *
     * @return array
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    /**
     * Returns the history.
     *
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }
}
