<?php

namespace Chess\Heuristics;

use Chess\Function\QuadraticFunction;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;

/**
 * SanHeuristics
 *
 * SanHeuristics transforms a chess game in SAN format to numbers for further
 * processing with ML techniques.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class SanHeuristics extends SanPlay
{
    /**
     * The evaluation function.
     *
     * @var \Chess\Function\QuadraticFunction
     */
    protected QuadraticFunction $function;

    /**
     * The balance.
     *
     * @var array
     */
    protected array $balance = [];

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param \Chess\Variant\Classical\Board|null $board
     */
    public function __construct(string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->function = new QuadraticFunction();

        $this->balance()->normalize(-1, 1);
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
     * Calculates the balance.
     *
     * @return \Chess\SanHeuristics
     */
    protected function balance(): SanHeuristics
    {
        $this->balance[] = (new FenHeuristics($this->board))->getBalance();
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $this->balance[] = (new FenHeuristics($this->board))->getBalance();
                }
            }
        }

        return $this;
    }

    /**
     * Normalizes the balance.
     *
     * @param int $newMin
     * @param int $newMax
     * @return \Chess\SanHeuristics
     */
    protected function normalize(int $newMin, int $newMax): SanHeuristics
    {
        if ($this->balance) {
            $columns = $mins = $maxs = $normd = $transpose = [];
            for ($i = 0; $i < count($this->function->getEval()); $i++) {
                $columns[$i] = array_column($this->balance, $i);
                $mins[$i] = round(min($columns[$i]), 2);
                $maxs[$i] = round(max($columns[$i]), 2);
            }
            for ($i = 0; $i < count($this->function->getEval()); $i++) {
                for ($j = 0; $j < count($columns[$i]); $j++) {
                    if ($columns[$i][$j] > 0) {
                        $normd[$i][$j] = round($columns[$i][$j] * $newMax / $maxs[$i], 2);
                    } elseif ($columns[$i][$j] < 0) {
                        $normd[$i][$j] = round($columns[$i][$j] * $newMin / $mins[$i], 2);
                    } else {
                        $normd[$i][$j] = 0;
                    }
                }
            }
            $this->balance = $this->transpose($normd);
        }

        return $this;
    }

    /**
     * Transposes the given array.
     *
     * @param array $normd
     * @return array
     */
    protected function transpose(array $normd): array
    {
        for ($i = 0; $i < count($normd); $i++) {
            for ($j = 0; $j < count($normd[$i]); $j++) {
                $transpose[$j][$i] = $normd[$i][$j];
            }
        }

        return $transpose;
    }
}
