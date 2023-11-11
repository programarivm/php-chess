<?php

namespace Chess;

use Chess\HeuristicsByFen;
use Chess\Eval\InverseEvalInterface;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Heuristics
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Heuristics extends SanPlay
{
    protected $heuristicsByFen;

    /**
     * The balanced heuristics.
     *
     * @var array
     */
    protected array $balance;

    /**
     * Constructor.
     *
     * @param string $movetext
     * @param Board|null $board
     */
    public function __construct(string $movetext = '', Board $board = null)
    {
        parent::__construct($movetext, $board);

        $this->heuristicsByFen = new HeuristicsByFen($this->board->toFen());

        $this->calc()->normalize();
    }

    /**
     * Calculates the heuristics.
     *
     * @return \Chess\Heuristics
     */
    protected function calc(): Heuristics
    {
        foreach ($this->sanMovetext->getMoves() as $key => $val) {
            if ($val !== Move::ELLIPSIS) {
                $turn = $this->board->getTurn();
                if ($this->board->play($turn, $val)) {
                    if (!empty($this->sanMovetext->getMoves()[$key+1])) {
                        $this->board->play(
                            Color::opp($turn),
                            $this->sanMovetext->getMoves()[$key+1]
                        );
                    }
                    $this->balance[] = (new HeuristicsByFen($this->board->toFen()))->getBalance();
                }
            }
        }

        return $this;
    }

    /**
     * Normalizes the chess evaluations.
     *
     * @return \Chess\Heuristics
     */
    protected function normalize(): Heuristics
    {
        $columns = [];
        $mins = [];
        $maxs = [];

        for ($i = 0; $i < count($this->heuristicsByFen->getEval()); $i++) {
            $columns[$i] = array_column($this->balance, $i);
            $mins[$i] = round(min($columns[$i]), 2);
            $maxs[$i] = round(max($columns[$i]), 2);
        }

        $normd = [];

        for ($i = 0; $i < count($this->heuristicsByFen->getEval()); $i++) {
            for ($j = 0; $j < count($columns[$i]); $j++) {
                if ($maxs[$i] - $mins[$i] > 0) {
                    $normd[$i][$j] = round(($columns[$i][$j] - $mins[$i]) / ($maxs[$i] - $mins[$i]), 2);
                } elseif ($maxs[$i] == $mins[$i]) {
                    $normd[$i][$j] = 0;
                }
            }
        }

        $transpose = [];

        for ($i = 0; $i < count($normd); $i++) {
            for ($j = 0; $j < count($normd[$i]); $j++) {
                $transpose[$j][$i] = $normd[$i][$j];
            }
        }

        $this->balance = $transpose;

        return $this;
    }

    /**
     * Returns the last element in the result.
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

    /**
     * Returns the balanced heuristics.
     *
     * @return array
     */
    public function getBalance(): array
    {
        return $this->balance;
    }
}
