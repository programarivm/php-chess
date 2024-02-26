<?php

namespace Chess\UciEngine\Details;

/**
 * UCI Limit Structure for handling the analysis limit.
 */
class Limit
{
    /**
     * Time to search in milliseconds.
     * @var int|null
     */
    public ?int $time;

    /**
     * Depth to search.
     * @var int|null
     */

    public ?int $depth;

    /**
     * Nodes to search.
     * @var int|null
     */
    public ?int $nodes;

    /**
     * Search for Mate in x moves. Not supported by all engines.
     * @var int|null
     */
    public ?int $mate;

    /**
     * Time to search in milliseconds.
     * @var int|null
     */
    public ?int $white_clock;

    /**
     * Time to search in milliseconds.
     * @var int|null
     */
    public ?int $black_clock;

    /**
     * Time to search in milliseconds.
     * @var int|null
     */
    public ?int $white_inc;

    /**
     * Time to search in milliseconds.
     * @var int|null
     */
    public ?int $black_inc;

    /**
     * Remaining moves to the next time control.
     * @var int|null
     */
    public ?int $remaining_moves;

    public function __construct($time = null, $depth = null, $nodes = null, $mate = null, $white_clock = null, $black_clock = null, $white_inc = null, $black_inc = null, $remaining_moves = null)
    {
        $this->time = $time;
        $this->depth = $depth;
        $this->nodes = $nodes;
        $this->mate = $mate;
        $this->white_clock = $white_clock;
        $this->black_clock = $black_clock;
        $this->white_inc = $white_inc;
        $this->black_inc = $black_inc;
        $this->remaining_moves = $remaining_moves;
    }
}
