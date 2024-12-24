<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * FEN Heuristics
 *
 * Discrete ternary oscillations in terms of heuristic evaluation features.
 */
class FenHeuristics
{
    /**
     * Discrete oscillations.
     *
     * @var array
     */
    public array $result = [];

    /**
     * The ternarized result.
     *
     * @var array
     */
    public array $balance = [];

    /**
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractFunction $f, AbstractBoard $board)
    {
        foreach ($f->eval as $val) {
            $item = EvalArray::add(new $val($board));
            $this->result[] = $item;
            $diff = $item[Color::W] - $item[Color::B];
            $this->balance[] = $diff > 0 ? 1 : ($diff < 0 ? -1 : 0);
        }
    }
}
