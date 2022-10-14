<?php

namespace Chess;

use Chess\Eval\InverseEvalInterface;
use Chess\Player\PgnPlayer;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board;

/**
 * Heuristics
 *
 * A Chess\Game object can be thought of in terms of snapshots describing what's
 * going on its Chess\Board as reported by a number of evaluation features.
 * PGN movetexts can be evaluated by considering those.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Heuristics extends PgnPlayer
{
    use HeuristicsTrait;

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param Board|null $board
     */
    public function __construct(string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->calc();
    }

    /**
     * Returns the dimensions names.
     *
     * @return array
     */
    public function getDimsNames(): array
    {
        $dimsNames = [];
        foreach ($this->dims as $key => $val) {
            $dimsNames[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $dimsNames;
    }

    /**
     * Returns the current evaluation of $this->board.
     *
     * The result obtained suggests which player may be better.
     *
     * @return array
     */
    public function eval(): array
    {
        $eval = [
            Color::W => 0,
            Color::B => 0,
        ];

        $weights = array_values($this->getDims());

        $result = $this->getResult();

        for ($i = 0; $i < count($this->getDims()); $i++) {
            $eval[Color::W] += $weights[$i] * end($result[Color::W])[$i];
            $eval[Color::B] += $weights[$i] * end($result[Color::B])[$i];
        }

        $eval[Color::W] = round($eval[Color::W], 2);
        $eval[Color::B] = round($eval[Color::B], 2);

        return $eval;
    }

    /**
     * Returns the resized balanced heuristics given a new range of values.
     *
     * @param float $newMin
     * @param float $newMax
     * @return array
     */
    public function getResizedBalance(float $newMin, float $newMax): array
    {
        $oldMin = -1;
        $oldMax = 1;
        $resize = [];
        foreach ($this->balance as $key => $val) {
            foreach ($val as $v) {
                $resized = (($v - $oldMin) / ($oldMax - $oldMin)) *
                    ($newMax - $newMin) + $newMin;
                $resize[$key][] = round($resized, 2);
            }
        }

        return $resize;
    }

    /**
     * Heuristics calc.
     *
     * @return \Chess\Heuristics
     */
    protected function calc(): Heuristics
    {
        foreach ($this->moves as $key => $val) {
            $turn = $this->board->getTurn();
            if ($key % 2 === 0) {
                $this->board->play($turn, $this->moves[$key]);
                $this->calcItem();
                empty($this->moves[$key+1])
                    ?: $this->board->play(Color::opp($turn), $this->moves[$key+1]);
                $this->calcItem();
            }
        }
        $this->normalize()->balance();

        return $this;
    }

    /**
     * Adds an item to $this->result.
     */
    protected function calcItem(): void
    {
        $item = [];
        foreach ($this->dims as $className => $weight) {
            $dimension = new $className($this->board);
            $eval = $dimension->eval();
            if (is_array($eval[Color::W])) {
                if ($dimension instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => count($eval[Color::B]),
                        Color::B => count($eval[Color::W]),
                    ];
                } else {
                    $item[] = [
                        Color::W => count($eval[Color::W]),
                        Color::B => count($eval[Color::B]),
                    ];
                }
            } else {
                if ($dimension instanceof InverseEvalInterface) {
                    $item[] = [
                        Color::W => $eval[Color::B],
                        Color::B => $eval[Color::W],
                    ];
                } else {
                    $item[] = $eval;
                }
            }
        }

        $this->result[Color::W][] = array_column($item, Color::W);
        $this->result[Color::B][] = array_column($item, Color::B);
    }

    /**
     * Normalizes the heuristic picture of $this->board.
     *
     * The dimensions are normalized meaning that the chess features (Material,
     * Center, Connectivity, Space, Pressure, K safety, Tactics, and so on)
     * are evaluated and scaled to have values between 0 and 1.
     *
     * It is worth noting that a normalized heuristic picture changes with every
     * chess move that is made because it is recalculated or zoomed out, if you like,
     * to fit within a 0–1 range.
     *
     * @return \Chess\Heuristics
     */
    protected function normalize(): Heuristics
    {
        $normd = [];
        if (count($this->board->getHistory()) >= 2) {
            for ($i = 0; $i < count($this->dims); $i++) {
                $values = [
                    ...array_column($this->result[Color::W], $i),
                    ...array_column($this->result[Color::B], $i)
                ];
                $min = round(min($values), 2);
                $max = round(max($values), 2);
                for ($j = 0; $j < count($this->result[Color::W]); $j++) {
                    if ($max - $min > 0) {
                        $normd[Color::W][$j][$i] =
                            round(($this->result[Color::W][$j][$i] - $min) / ($max - $min), 2);
                        $normd[Color::B][$j][$i] =
                            round(($this->result[Color::B][$j][$i] - $min) / ($max - $min), 2);
                    } elseif ($max == $min) {
                        $normd[Color::W][$j][$i] = 0;
                        $normd[Color::B][$j][$i] = 0;
                    }
                }
            }
        } else {
            $normd[Color::W][] =
                $normd[Color::B][] = array_fill(0, count($this->dims), 0);
        }

        $this->result = $normd;

        return $this;
    }

    /**
     * Balances the heuristic picture of $this->board.
     *
     * A chess game can be plotted in terms of balance. +1 is the best possible
     * evaluation for White and -1 the best possible evaluation for Black. Both
     * forces being set to 0 means they're actually offset and, therefore, balanced.
     *
     * @return \Chess\Heuristics
     */
    protected function balance(): Heuristics
    {
        foreach ($this->result[Color::W] as $i => $color) {
            foreach ($color as $j => $val) {
                $this->balance[$i][$j] =
                    round($this->result[Color::W][$i][$j] - $this->result[Color::B][$i][$j], 2);
            }
        }

        return $this;
    }

    /**
     * Returns the last element of the heuristic picture.
     *
     * @return array
     */
    public function end(): array
    {
        return [
            Color::W => end($this->result[Color::W]),
            Color::B => end($this->result[Color::B]),
        ];
    }
}
