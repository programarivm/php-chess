<?php

namespace Chess;

use Chess\Eval\InverseEvalInterface;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * FenHeuristics
 *
 * FenHeuristics transforms a FEN position to numbers for further processing
 * with ML techniques.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class FenHeuristics
{
    /**
     * Chess board.
     *
     * @var \Chess\Variant\Classical\Board
     */
    protected Board $board;

    /**
     * The evaluation function.
     *
     * @var \Chess\Function\StandardFunction
     */
    protected StandardFunction $function;

    /**
     * The result.
     *
     * @var array
     */
    protected array $result;

    /**
     * The balance.
     *
     * @var array
     */
    protected array $balance = [];

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->function = new StandardFunction();

        $this->calc()->balance();
    }

    /**
     * Returns the balance.
     *
     * @return array
     */
    public function getBalance(): array
    {
        return $this->balance;
    }

    /**
     * Calculates the result.
     *
     * @return FenHeuristics
     */
    protected function calc(): FenHeuristics
    {
        foreach ($this->function->getEval() as $val) {
            $eval = new $val($this->board);
            $result = $eval->getResult();
            if (is_array($result[Color::W])) {
                if ($eval instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => count($result[Color::B]),
                        Color::B => count($result[Color::W]),
                    ];
                } else {
                    $item[] = [
                        Color::W => count($result[Color::W]),
                        Color::B => count($result[Color::B]),
                    ];
                }
            } else {
                if ($eval instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => $result[Color::B],
                        Color::B => $result[Color::W],
                    ];
                } else {
                    $item[] = $result;
                }
            }
        }

        $this->result[Color::W] = array_column($item, Color::W);
        $this->result[Color::B] = array_column($item, Color::B);

        return $this;
    }

    /**
     * Calculates the balance.
     *
     * @return FenHeuristics
     */
    protected function balance(): FenHeuristics
    {
        foreach ($this->result[Color::W] as $key => $val) {
            $this->balance[$key] =
                round($this->result[Color::W][$key] - $this->result[Color::B][$key], 2);
        }

        return $this;
    }
}
