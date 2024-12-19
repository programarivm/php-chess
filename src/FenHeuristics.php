<?php

namespace Chess;

use Chess\Eval\InverseEvalInterface;
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
     * @param \Chess\Function\AbstractFunction $function
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractFunction $function, AbstractBoard $board)
    {
        foreach ($function->eval as $val) {
            $eval = new $val($board);
            $item = $eval->result;
            if (is_array($item[Color::W])) {
                if ($eval instanceof InverseEvalInterface) {
                    $item = [
                        Color::W => count($item[Color::B]),
                        Color::B => count($item[Color::W]),
                    ];
                } else {
                    $item = [
                        Color::W => count($item[Color::W]),
                        Color::B => count($item[Color::B]),
                    ];
                }
            } else {
                if ($eval instanceof InverseEvalInterface) {
                    $item = [
                        Color::W => $item[Color::B],
                        Color::B => $item[Color::W],
                    ];
                }
            }
            $this->result[] = $item;
            $diff = $item[Color::W] - $item[Color::B];
            $this->balance[] = $diff > 0 ? 1 : ($diff < 0 ? -1 : 0);
        }
    }
}
