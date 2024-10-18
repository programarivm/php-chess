<?php

namespace Chess\UciEngine\Details;

/**
 * UCI Limit for handling the analysis limit.
 */
class Limit
{
    /**
     * Time to search in milliseconds.
     *
     * @var int|null
     */
    public ?int $movetime;

    /**
     * Depth to search.
     *
     * @var int|null
     */

    public ?int $depth;

    /**
     * Nodes to search.
     *
     * @var int|null
     */
    public ?int $nodes;

    /**
     * Search for Mate in x moves. Not supported by all engines.
     *
     * @var int|null
     */
    public ?int $mate;

    /**
     * White has x msec left on the clock.
     *
     * @var int|null
     */
    public ?int $wtime;

    /**
     * Black has x msec left on the clock.
     *
     * @var int|null
     */
    public ?int $btime;

    /**
     * White increment per move in mseconds.
     *
     * @var int|null
     */
    public ?int $winc;

    /**
     * Black increment per move in mseconds.
     *
     * @var int|null
     */
    public ?int $binc;

    /**
     * Remaining moves to the next time control.
     *
     * @var int|null
     */
    public ?int $movestogo;

    /**
     * Constructor.
     *
     * @param int|null $movetime
     * @param int|null $depth
     * @param int|null $nodes
     * @param int|null $mate
     * @param int|null $wtime
     * @param int|null $btime
     * @param int|null $winc
     * @param int|null $binc
     * @param int|null $movestogo
     */
    public function __construct(
        $movetime = null,
        $depth = null,
        $nodes = null,
        $mate = null,
        $wtime = null,
        $btime = null,
        $winc = null,
        $binc = null,
        $movestogo = null
    ) {
        $this->movetime = $movetime;
        $this->depth = $depth;
        $this->nodes = $nodes;
        $this->mate = $mate;
        $this->wtime = $wtime;
        $this->btime = $btime;
        $this->winc = $winc;
        $this->binc = $binc;
        $this->movestogo = $movestogo;
    }
}
