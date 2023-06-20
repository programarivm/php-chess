<?php

namespace Chess\Play;

use Chess\Variant\Classical\Board;

/**
 * AbstractPlay.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractPlay
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
     * FEN history.
     *
     * @var array
     */
    protected array $fen;

    /**
     * Returns the chessboard object.
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
     * Returns the FEN history.
     *
     * @return array
     */
    public function getFen(): array
    {
        return $this->fen;
    }
}
