<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Heuristics
 *
 * Continuous oscillations in terms of heuristic evaluation features.
 */
class SanHeuristics extends SanPlay
{
    /**
     * Function.
     *
     * @var \Chess\Function\AbstractFunction
     */
    public AbstractFunction $function;

    /**
     * The name of the evaluation feature.
     *
     * @var string
     */
    public string $name;

    /**
     * Continuous oscillations.
     *
     * @var array
     */
    public array $result = [];

    /**
     * The balanced normalized result.
     *
     * @var array
     */
    public array $balance = [];

    /**
     * @param \Chess\Function\AbstractFunction $function
     * @param string $movetext
     * @param string $name
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(
        AbstractFunction $function,
        string $movetext = '',
        string $name = '',
        AbstractBoard $board = null
    ) {
        parent::__construct($movetext, $board);

        $this->function = $function;
        $this->name = $name;

        $this->calc()->balance()->normalize(-1, 1);
    }

    /**
     * Calculates the result.
     *
     * @return \Chess\SanHeuristics
     */
    protected function calc(): SanHeuristics
    {
        $this->result[] = $this->item(EvalFactory::create(
            $this->function,
            $this->name,
            $this->board
        ));

        foreach ($this->sanMovetext->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $move)) {
                    $this->result[] = $this->item(EvalFactory::create(
                        $this->function,
                        $this->name,
                        $this->board
                    ));
                }
            }
        }

        return $this;
    }

    /**
     * Balances the result.
     *
     * @return \Chess\SanHeuristics
     */
    protected function balance(): SanHeuristics
    {
        foreach ($this->result as $result) {
            $this->balance[] = $result[Color::W] - $result[Color::B];
        }

        return $this;
    }

    /**
     * Normalizes the balance.
     *
     * @param int $newMin
     * @param int $newMax
     */
    protected function normalize(int $newMin, int $newMax): void
    {
        $min = min($this->balance);
        $max = max($this->balance);

        foreach ($this->balance as $key => $val) {
            if ($val > 0) {
                $this->balance[$key] = round($this->balance[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $this->balance[$key] = round($this->balance[$key] * $newMin / $min, 2);
            } else {
                $this->balance[$key] = 0;
            }
        }
    }

    /**
     * Calculates an item.
     *
     * @param \Chess\Eval\AbstractEval $eval
     * @return array
     */
    protected function item(AbstractEval $eval): array
    {
        $result = $eval->result;

        if (is_array($result[Color::W])) {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => count($result[Color::B]),
                    Color::B => count($result[Color::W]),
                ];
            } else {
                $item = [
                    Color::W => count($result[Color::W]),
                    Color::B => count($result[Color::B]),
                ];
            }
        } else {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => $result[Color::B],
                    Color::B => $result[Color::W],
                ];
            } else {
                $item = $result;
            }
        }

        return $item;
    }
}
