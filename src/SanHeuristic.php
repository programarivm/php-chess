<?php

namespace Chess;

use Chess\EvalFactory;
use Chess\Eval\AbstractEval;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SanHeuristic
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class SanHeuristic extends SanPlay
{
    /**
     * The name of the evaluation feature.
     *
     * @var string
     */
    protected string $name;

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
     * @param string $name
     * @param string $movetext
     * @param \Chess\Variant\Classical\Board|null $board
     */
    public function __construct(string $name, string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->name = $name;

        $this->calc()->balance()->normalize(-1, 1);
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
     * @return \Chess\SanHeuristic
     */
    protected function calc(): SanHeuristic
    {
        $this->result[] = $this->item(EvalFactory::create($this->name, $this->board));
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $this->result[] = $this->item(EvalFactory::create($this->name, $this->board));
                }
            }
        }

        return $this;
    }

    /**
     * Returns an item.
     *
     * @param \Chess\Eval\AbstractEval
     * @return array
     */
    protected function item(AbstractEval $eval): array
    {
        $result = $eval->getResult();

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

    /**
     * Calculates the balance.
     *
     * @return SanHeuristic
     */
    protected function balance(): SanHeuristic
    {
        foreach ($this->result as $key => $val) {
            $this->balance[$key] =
                round($val[Color::W] - $val[Color::B], 2);
        }

        return $this;
    }

    /**
     * Normalizes the balance.
     *
     * @param int $newMin
     * @param int $newMax
     * @return \Chess\Heuristic\SanHeuristic
     */
    protected function normalize(int $newMin, int $newMax): SanHeuristic
    {
        $normd = [];
        $min = min($this->balance);
        $max = max($this->balance);
        foreach ($this->balance as $key => $val) {
            if ($val > 0) {
                $normd[$key] = round($this->balance[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $normd[$key] = round($this->balance[$key] * $newMin / $min, 2);
            } else {
                $normd[$key] = 0;
            }
        }

        $this->balance = $normd;

        return $this;
    }
}
